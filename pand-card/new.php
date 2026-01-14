<?php
function skarabee_list_card ( $publication ) {
    $property = $publication->Property;

    // Details page URL — adapt to your own permalink structure
    $details_url = site_url( '/detail/?id=' . $publication->Info->ID );

    // First image (Skarabee Weblink standard)
    $imageUrl = null;
    if ( ! empty( $publication->Pictures ) && is_array( $publication->Pictures ) ) {
        $imageUrl = $publication->Pictures[0]->Url ?? null;
    }

    // Fallback image
    if ( ! $imageUrl ) {
        $imageUrl = 'https://placehold.co/600x400?text=Geen+afbeelding';
    }

    // Status (For sale, For rent, Sold, etc.)
    $status = skarabee_map_status( $property->Status );
    $type   = skarabee_map_type( $property->Type );

    // Title / description
    $title = $property->Flashes[0]->Title ?? '';

    // Format price
    $price = number_format( $property->Price, 0, ',', '.' );

    // City
    $city = $property->Address->City->Value ?? '';

    // Street + house number
    $street  = $property->Address->Street ?? '';
    $houseNr = $property->Address->HouseNumber ?? '';

    // Bedrooms + bathrooms
    $bedrooms  = $property->NumberOfBedrooms ?? 0;
    $bathrooms = $property->NumberOfBathrooms ?? 0;

    // Area
    $surfaceArea = $property->SurfaceLivable ?? 0;
    ?>

    <a href="<?php echo $details_url; ?>" class="card aanbod-card <?php if ( $property->Status === 'SOLD' ) echo ' is-sold'; ?>">
        <div class="image-wrapper">
            <img src="<?php echo $imageUrl; ?>" alt="">
            <span class="sold-overlay">
                Verkocht?<br>
                <small>Zeker en vast!</small>
            </span>
        </div>

        <div class="content-wrapper">
            <div class="tag-wrapper">
                <p class="tag"><?php echo $status; ?></p>
            </div>

            <div class="type-wrapper">
                <p class="type"><?php echo $type; ?></p>
            </div>

            <div class="inner-wrapper">
                <div class="column">
                    <h3 class="title"><?php echo $street . ' ' . $houseNr; ?></h3>
                    <p class="location title"><?php echo $city; ?></p>
                    <p class="price title bold">€<?php echo $price; ?></p>
                    <p class="description"><?php echo $title; ?></p>
                </div>

                <div class="column">
                    <div class="property-meta">
                        <ul class="meta-grid" role="list">

                            <li class="meta-item">
                                <img src="https://sgntr.be/wp-content/uploads/2025/12/AFMETING_ICON.svg" alt="" class="meta-icon" aria-hidden="true">
                                <span class="meta-text"><?php echo $surfaceArea; ?>m²</span>
                            </li>

                            <li class="meta-item">
                                <img src="https://sgntr.be/wp-content/uploads/2025/12/SLAAPKAMERS_ICON.svg" alt="" class="meta-icon" aria-hidden="true">
                                <span class="meta-text"><?php echo $bedrooms; ?> Slaapkamers</span>
                            </li>

                            <li class="meta-item">
                                <img src="https://sgntr.be/wp-content/uploads/2025/12/BADKAMER_ICON.svg" alt="" class="meta-icon" aria-hidden="true">
                                <span class="meta-text"><?php echo $bathrooms; ?> Badkamers</span>
                            </li>

                            <?php if ( $property->NumberOfGarages > 0 ) : ?>
                                <li class="meta-item">
                                    <img src="https://sgntr.be/wp-content/uploads/2025/12/GARAGE_ICON.svg" alt="" class="meta-icon" aria-hidden="true">
                                    <span class="meta-text">Parkeergarage</span>
                                </li>
                            <?php endif; ?>

                        </ul>

                        <!-- geen <a> meer, maar visueel blijft het een knop -->
                        <span class="meta-cta">meer info</span>
                    </div>
                </div>
            </div>
        </div>
    </a>

    <?php
}

function add_last_card() {
    ?>
    <div class="card aanbod-card cta-card">
        <div class="inner-container">
            <h3>
                Benieuwd hoe wij de verkoop van uw pand aanpakken?
            </h3>
            <p>
                Vraag nu je gratis verkoop analyse aan!
            </p>
            <a href="#" class="meta-cta button-cta orange-btn">
                <span class="button-cta-text">aanvragen</span>
                <span class="fill-container" aria-hidden="true"></span>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Render cards: 5 aanbod cards -> CTA card -> opnieuw 5 -> ...
 * EN ook altijd een finale CTA card op het einde (als er nog geen CTA kwam op exact het einde).
 *
 * Gebruik:
 *   skarabee_render_cards_with_cta( $publications );
 */
function skarabee_render_cards_with_cta( $publications ) {

    if ( empty( $publications ) || ! is_array( $publications ) ) {
        return;
    }

    $counter = 0;

    foreach ( $publications as $publication ) {

        skarabee_list_card( $publication );
        $counter++;

        // Na elke 5 aanbod cards: CTA card
        if ( $counter % 5 === 0 ) {
            add_last_card();
        }
    }

    // Finale CTA card indien laatste batch geen veelvoud van 5 was
    if ( $counter > 0 && $counter % 5 !== 0 ) {
        add_last_card();
    }
}