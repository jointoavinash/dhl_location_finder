namespace Drupal\dhl_location_finder\Service;

use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;

class LocationService {

  protected ClientInterface $httpClient;
  protected LoggerInterface $logger;

  public function __construct(ClientInterface $httpClient, LoggerInterface $logger) {
    $this->httpClient = $httpClient;
    $this->logger = $logger;
  }

  public function getLocations(string $country, string $city, string $postalCode): array {
    try {
      $response = $this->httpClient->request('GET', 'https://api.dhl.com/location-finder', [
        'headers' => [
          'DHL-API-Key' => 'demo-key'
        ],
        'query' => [
          'countryCode' => $country,
          'city' => $city,
          'postalCode' => $postalCode,
        ]
      ]);

      $data = json_decode($response->getBody()->getContents(), TRUE);
      return $this->filterLocations($data['locations'] ?? []);
    }
    catch (\Exception $e) {
      $this->logger->error('DHL API Error: @message', ['@message' => $e->getMessage()]);
      return [];
    }
  }

  private function filterLocations(array $locations): array {
    return array_filter($locations, function ($location) {
      $address = $location['address']['streetAddress'] ?? '';

      // Filter out odd numbers
      if (preg_match('/\d+/', $address, $matches) && $matches[0] % 2 !== 0) {
        return FALSE;
      }

      // Check for weekend hours
      $weekendDays = ['saturday', 'sunday'];
      foreach ($weekendDays as $day) {
        if (empty($location['openingHours'][$day])) {
          return FALSE;
        }
      }

      return TRUE;
    });
  }

  public function formatToYaml(array $locations): string {
    $formatted = [];
    foreach ($locations as $location) {
      $formatted[] = [
        'locationName' => $location['locationName'],
        'address' => $location['address'],
        'openingHours' => $location['openingHours'],
      ];
    }

    return Yaml::dump($formatted, 2, 2);
  }
}