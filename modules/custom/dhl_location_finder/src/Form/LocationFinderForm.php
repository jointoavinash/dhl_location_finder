namespace Drupal\dhl_location_finder\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\dhl_location_finder\Service\LocationService;

class LocationFinderForm extends FormBase {

  protected LocationService $locationService;

  public function __construct(LocationService $locationService) {
    $this->locationService = $locationService;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('dhl_location_finder.location_service')
    );
  }

  public function getFormId() {
    return 'dhl_location_finder_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#required' => TRUE,
    ];
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#required' => TRUE,
    ];
    $form['postal_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Postal Code'),
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Find Locations'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $country = $form_state->getValue('country');
    $city = $form_state->getValue('city');
    $postalCode = $form_state->getValue('postal_code');

    $locations = $this->locationService->getLocations($country, $city, $postalCode);
    $output = $this->locationService->formatToYaml($locations);

    $this->messenger()->addMessage(nl2br($output));
  }
}