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

  if ($heating === 'gas') {
      $heatingResult = 'Verwarming op gas';
  } elseif ($heating === 'electricity') {
      $heatingResult = 'Elektrische verwarming';
  }

  $cadastrallIncome = $property->CadastrallIncome ?? 'onbekend';
  $cadastrallName = $property->CadastrallName ?? 'onbekend';
  $cadastrallNumbers = !empty($property->CadastrallNumbers) ? $property->CadastrallNumbers : 'onbekend';

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

  // Raamwerk
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

  // Energy Certificate Info
  $energyCertificateNr = 'onbekend';
  $energyClass = 'onbekend';
  $energyClassEndDate = 'onbekend';
  $energyIndex = 'onbekend';

  if (isset($property->Energy->Index)) {
    $energyCertificateNr = $property->Energy->EnergyCertificateNr ?? 'onbekend';
    $energyClass = $property->Energy->Class ?? 'onbekend';
    $energyClassEndDate = $property->Energy->ClassEndDate ?? 'onbekend';
    $energyIndex = $property->Energy->Index;
  }

  // Additional Legal Information
  $environmentalPlanning = $property->EnvironmentalPlanning ?? 'onbekend';
  
  // Urban Development (Stedenbouwkundige informatie)
  $urbanDev = $property->UrbanDev ?? null;
  $permit = $trueFalseOrUnknown[$urbanDev->Permit ?? 'UNDEFINED'] ?? 'Onbekend';
  $summons = $trueFalseOrUnknown[$urbanDev->Summons ?? 'UNDEFINED'] ?? 'Nee';
  $preemptiveRights = $trueFalseOrUnknown[$urbanDev->PreemptiveRights ?? 'UNDEFINED'] ?? 'Nee';
  $allotmentPermit = $trueFalseOrUnknown[$urbanDev->AllotmentPermit ?? 'UNDEFINED'] ?? 'Nee';
  
  // Area Application (Bestemmingsplan)
  $areaApplication = 'Onbekend';
  if (isset($urbanDev->AreaApplication)) {
      $areaApplication = $urbanDev->AreaApplication;
  }
  
  // Judicial Decision
  $judicialDecisionTypes = [
      'UNDEFINED' => 'Onbekend',
      'Dv' => 'Dagvaarding',
      'Ho' => 'Herstelvordering',
      'Bo' => 'Bouwovertreding',
      'Lod' => 'Lopende procedure',
      'Ms' => 'Milieuschade',
      'Gmo' => 'Geen meldingen',
  ];
  $judicialDecision = $trueFalseOrUnknown[$urbanDev->JudicialDecision ?? 'UNDEFINED'] ?? 'Nee';
  $judicialDecisionType = $judicialDecisionTypes[$urbanDev->JudicialDecisionType ?? 'UNDEFINED'] ?? 'Onbekend';
  
  // Protected Monument
  $protectedMonumentTypes = [
      'UNDEFINED' => 'Onbekend',
      'NOT_PROTECTED' => 'Niet beschermd',
      'PROTECTED' => 'Beschermd',
      'INVENTORIED' => 'Geïnventariseerd',
  ];
  $protectedMonument = $protectedMonumentTypes[$urbanDev->ProtectedMonument ?? 'UNDEFINED'] ?? 'Onbekend';
  
  // Flood Risk (Overstromingsgevoeligheid)
  $floodProneAreaTypes = [
      'UNDEFINED' => 'Onbekend',
      'EFFECTIVE' => 'Effectief overstromingsgevoelig',
      'POSSIBLE' => 'Mogelijk overstromingsgevoelig gebied',
      'NONE' => 'Geen overstromingsgevoelig gebied',
  ];
  $floodProneArea = $floodProneAreaTypes[$property->FloodProneArea ?? 'UNDEFINED'] ?? 'Onbekend';
  
  $delimitedFloodplainTypes = [
      'UNDEFINED' => 'Onbekend',
      'FLOODPLAIN' => 'Afgebakende overstromingsgebied',
      'RIVERBANK' => 'Afgebakende oeverzone',
      'NONE' => 'Geen afgebakende zones',
  ];
  $delimitedFloodplain = $delimitedFloodplainTypes[$property->DelimitedFloodplainOrRiverbank ?? 'UNDEFINED'] ?? 'Onbekend';
  
  // Heritage Protection (Erfgoed)
  $heritage = 'Geen beschermd erfgoed';
  if ($protectedMonument === 'Beschermd') {
      $heritage = 'Beschermd erfgoed';
  } elseif ($protectedMonument === 'Geïnventariseerd') {
      $heritage = 'Geïnventariseerd erfgoed';
  }

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
                  <!-- EPC Information -->
                  <li><span>EPC Certificaat Nr.:</span> <strong><?php echo esc_html($energyCertificateNr); ?></strong></li>
                  <li><span>EPC Index:</span> <strong><?php echo esc_html($energyIndex); ?> kWh/(m² jaar)</strong></li>
                  <li><span>Energielabel:</span> <strong><?php echo esc_html($energyClass); ?></strong></li>
                  <li><span>EPC Geldig tot:</span> <strong><?php echo esc_html($energyClassEndDate); ?></strong></li>
                  
                  <!-- Urban Planning -->
                  <li><span>Stedenbouwkundige vergunning:</span> <strong><?php echo esc_html($permit); ?></strong></li>
                  <li><span>Voorkooprecht:</span> <strong><?php echo esc_html($preemptiveRights); ?></strong></li>
                  <li><span>Verkavelingsvergunning van toepassing:</span> <strong><?php echo esc_html($allotmentPermit); ?></strong></li>
                  <li><span>Stedenbouwkundige bestemming:</span> <strong><?php echo esc_html($areaApplication); ?></strong></li>
                  
                  <!-- Flood Risk -->
                  <li><span>Overstromingsgevoelig:</span> <strong><?php echo esc_html($floodProneArea); ?></strong></li>
                  <li><span>Overstromingsgebied:</span> <strong><?php echo esc_html($delimitedFloodplain); ?></strong></li>
                  
                  <!-- Heritage -->
                  <li><span>Erfgoed:</span> <strong><?php echo esc_html($heritage); ?></strong></li>
              </ul>
          </div>
      </div>
  </div>
</div>

<?php
  return ob_get_clean();
}
?>