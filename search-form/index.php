add_shortcode('skarabee_search_form', function() {
    $cities = get_cities_list();

    $types = SKARABEE_TYPE_MAP;

    $action_url = site_url('/aanbod');

    ob_start();
    ?>
    <form method="GET" class="skarabee-search-form" action="<?php echo esc_url($action_url); ?>">
        
        <label>Ik wil...</label>
        <select name="status">
            <option value="">Any</option>
            <option value="FOR_SALE">Kopen</option>
            <option value="FOR_RENT">Huren</option>
        </select>

        <label>Regio:</label>
        <select name="city">
            <option value="">Alle</option>
            <?php foreach ($cities as $c): ?>
                <option value="<?php echo esc_attr($c); ?>">
                    <?php echo esc_html($c); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Ik zoek...</label>
        <select name="type">

            <?php foreach (skarabee_simplified_type_groups() as $key => $group): ?>
                <option value="<?php echo esc_attr($key); ?>">
                    <?php echo esc_html($group['label']); ?>
                </option>
            <?php endforeach; ?>
        </select>


        <select name="bedrooms">
            <option value="">0+ Slaapkamers</option>
            <option value="1">1+ Slaapkamers</option>
            <option value="2">2+ Slaapkamers</option>
            <option value="3">3+ Slaapkamers</option>
            <option value="4">4+ Slaapkamers</option>
        </select>



        <button type="submit">Zoeken</button>
    </form>
    <?php

    return ob_get_clean();
});