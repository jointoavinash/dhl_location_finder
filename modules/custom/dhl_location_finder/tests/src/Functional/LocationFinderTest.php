namespace Drupal\Tests\dhl_location_finder\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test the DHL Location Finder module.
 *
 * @group dhl_location_finder
 */
class LocationFinderTest extends BrowserTestBase {

  protected static $modules = ['dhl_location_finder'];

  public function testLocationForm() {
    $this->drupalGet('dhl-location-finder');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->fieldExists('country');
    $this->assertSession()->fieldExists('city');
    $this->assertSession()->fieldExists('postal_code');
  }
}