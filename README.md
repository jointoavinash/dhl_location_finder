# dhl_location_finder
Location Finder

Folder Structure
dhl_location_finder/

├── dhl_location_finder.info.yml
├── dhl_location_finder.routing.yml
├── src/
│   ├── Controller/
│   │   └── LocationController.php
│   ├── Form/
│   │   └── LocationForm.php
│   └── Service/
│       └── LocationService.php
└── tests/
    └── Functional/
        └── LocationFinderTest.php


# Installation:
1. Place all the content from *DHL_LOCATION_FINDER* folder to `web/` at root level.
2. Enable the module using Drush or Drupal UI: 
`
drush en dhl_location_finder -y
`
3. Visit /dhl-location-finder to use the form.
