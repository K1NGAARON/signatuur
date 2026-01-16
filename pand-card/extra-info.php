<?php

function get_extra_info_panel($publication) {

  $property = $publication->Property;

  $kitchenTypes = [
      'UNDEFINED' => 'Onbekend',
      'BUILD_WITHOUT_APPLIANCES' => 'Gebouwd zonder apparatuur',
      'BUILD_WITH_APPLIANCES' => 'Gebouwd met apparatuur',
      'OPEN_KITCHEN' => 'Open keuken',
  ];

  $kitchenType = $kitchenTypes[$property->KitchenType] ?? 'Onbekend';

  $heating = resolve_heating_energy($property->HeatingTypes);

  $heatingResult = 'Onbekend';

  //fucking bagger checks below
  if ($heating === 'gas') {
      $heatingResult = 'Verwarming op gas';
  } elseif ($heating === 'electricity') {
      $heatingResult = 'Elektrische verwarming';
  }

  $cadastrallIncome = $property->CadastrallIncome ?? 'onbekend';
  $cadastrallName = $property->CadastrallName ?? 'onbekend';
  $cadastrallNumbers;

  if (!empty($property->CadastrallNumbers)) {
      $cadastrallNumbers = $property->CadastrallNumbers;
  } else {
      $cadastrallNumbers = 'onbekend';
  }

  // checks if field is true, false or undefined as its a string from the API
  $trueFalseOrUnknown = [
    'TRUE' => 'Ja',
    'FALSE' => 'Nee',
    'UNDEFINED' => 'Onbekend',
  ];
  // Afstanden points of interest
  $nearbyPublicTransport = $trueFalseOrUnknown[$property->NearbyPublicTransport] ?? 'Onbekend';
  $nearbySchool = $trueFalseOrUnknown[$property->NearbySchool] ?? 'Onbekend';
  $nearbyShops = $trueFalseOrUnknown[$property->NearbyShops] ?? 'Onbekend';
  $nearbyHighway = $trueFalseOrUnknown[$property->NearbyHighway] ?? 'Onbekend';

  //Raamwerk
  $windowTypes = [
      'UNDEFINED' => 'Onbekend',
      'WOOD' => 'Hout',
      'PVC' => 'PVC',
      'ALUMINIUM' => 'Aluminium',
  ];

  $windowType = $windowTypes[$property->WindowType] ?? 'Onbekend';

  // Beglazing
  $beglazingTypes = [
    'UNDEFINED' => 'Onbekend',
    'SINGLE' => 'Enkel glas',
    'DOUBLE' => 'Dubbel glas',
    'TRIPLE' => 'Driedubbel glas',
    'PARTLY_DOUBLE' => 'Gedeeltelijk dubbel glas',
    'HIGHEFFICIENCY' => 'Hoogrendementsglas',
    'SUPERINSULATING' => 'Superisolerend glas',
    'BAY' => 'Beglazing met spouw',     
  ];
  
  $beglazingType = $beglazingTypes[$property->GlazingTypes[0] ?? 'UNDEFINED'] ?? 'Onbekend';

  $energyCertificateNr = 'onbekend';
  $energyClass = 'onbekend';
  $energyClassEndDate = 'onbekend';


  // if it has no index I assume no energy certificate
  if ($property->Energy->Index) {
    $energyCertificateNr = $property->Energy->EnergyCertificateNr;
    $energyClass = $property->Energy->Class;
    $energyClassEndDate = $property->Energy->ClassEndDate;
  }
  $energyIndexYearlyTotal = $property->EnergyIndexYearlyTotal ?? 'onbekend';

  $environmentalPlanning = $property->EnvironmentalPlanning ?? 'onbekend';
  ob_start();

    ?>

    <div class="voorzieningen-wrapper">
        <div class="tabs" data-tabs>
          <div class="tabs-nav" role="tablist" aria-label="Voorzieningen">
              <button class="tab is-active" role="tab" aria-selected="true" aria-controls="panel-algemeen" id="tab-algemeen">
                  Algemeen
              </button>
              <button class="tab" role="tab" aria-selected="false" aria-controls="panel-indeling" id="tab-indeling">
                  Indeling
              </button>
              <button class="tab" role="tab" aria-selected="false" aria-controls="panel-comfort" id="tab-comfort">
                  Comfort
              </button>
              <button class="tab" role="tab" aria-selected="false" aria-controls="panel-wettelijk" id="tab-wettelijk">
                  Wettelijke gegevens
              </button>
          </div>

          <div class="tabs-content">
              <div class="tab-panel is-active" role="tabpanel" id="panel-algemeen" aria-labelledby="tab-algemeen">
                  <ul class="spec-list">
                      <li><span>Niet geïndexeerd K.I.:</span> <strong><?php echo esc_html($cadastrallIncome); ?></strong></li>
                      <li><span>Kadastrale benaming:</span> <strong><?php echo esc_html($cadastrallName); ?></strong></li>
                      <li><span>Kadastrale Nummers:</span> <strong><?php echo esc_html($cadastrallNumbers); ?></strong></li>
                  </ul>
              </div>

              <div class="tab-panel" role="tabpanel" id="panel-indeling" aria-labelledby="tab-indeling" hidden>
                  <ul class="spec-list">
                      <li><span>Slaapkamers:</span> <strong><?php echo esc_html($property->NumberOfBedrooms); ?></strong></li>
                      <li><span>Badkamers:</span> <strong><?php echo esc_html($property->NumberOfBathrooms); ?></strong></li>
                  </ul>
              </div>

              <div class="tab-panel" role="tabpanel" id="panel-comfort" aria-labelledby="tab-comfort" hidden>
                  <ul class="spec-list">
                      <li><span>Keuken:</span> <strong><?php echo esc_html($kitchenType); ?></strong></li>
                      <li><span>Verwarming:</span> <strong><?php echo esc_html($heatingResult); ?></strong></li>
                      <li><span>Raamwerk:</span> <strong><?php echo esc_html($windowType); ?></strong></li>
                      <li><span>Beglazing:</span> <strong><?php echo esc_html($beglazingType); ?></strong></li>
                      <li><span>Openbaar vervoer nabij:</span> <strong><?php echo esc_html($nearbyPublicTransport); ?></strong></li>
                      <li><span>School nabij:</span> <strong><?php echo esc_html($nearbySchool); ?></strong></li>
                      <li><span>Winkels nabij:</span> <strong><?php echo esc_html($nearbyShops); ?></strong></li>
                      <li><span>Autosnelweg nabij:</span> <strong><?php echo esc_html($nearbyHighway); ?></strong></li>
                  </ul>
              </div>

              <div class="tab-panel" role="tabpanel" id="panel-wettelijk" aria-labelledby="tab-wettelijk" hidden>
                  <ul class="spec-list">
                      <li><span>EPC Certificaat Nr.:</span> <strong><?php echo esc_html($energyCertificateNr); ?></strong></li>
                      <li><span>EPC Index:</span> <strong><?php echo esc_html($energyIndexYearlyTotal); ?> kWh/(m² jaar)</strong></li>
                      <li><span>Energielabel:</span> <strong><?php echo esc_html($energyClass); ?></strong></li>
                      <li><span>EPC Geldig tot:</span> <strong><?php echo esc_html($energyClassEndDate); ?></strong></li>
                  </ul>
              </div>
          </div>
      </div>
  </div>
  <?php
  return ob_get_clean();
}