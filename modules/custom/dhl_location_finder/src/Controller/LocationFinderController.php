namespace Drupal\dhl_location_finder\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\dhl_location_finder\Service\LocationService;

class LocationFinderController extends ControllerBase {

  protected LocationService $locationService;

  public function __construct(LocationService $locationService) {
    $this->locationService = $locationService;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('dhl_location_finder.location_service')
    );
  }

  public function content() {
    return [
      '#markup' => 'DHL Location Finder Module',
    ];
  }
}