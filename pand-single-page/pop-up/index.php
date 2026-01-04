 <?php

 function get_contact_form( $pub_id ) {
     ?>
    <form method="post" class="skarabee-contact-form booking-form-wrapper">
        <div class="wrapper">
            <input type="hidden" name="action" value="skarabee_contact">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('skarabee_contact_nonce'); ?>">
            <input type="hidden" name="publication_id" value="<?php echo $pub_id; ?>">
        </div>

        <div class="wrapper">
            <div class="item">
                <input name="first_name" placeholder="Voornaam" required>
                <input name="email" type="email" placeholder="E-mail" required>
            </div>
            <div class="item">
                <input name="last_name" placeholder="Achternaam" required>
                <input name="phone" type="tel" placeholder="Telefoonnummer">
            </div>
        </div>

        <div class="wrapper">
            <div class="item">
                <label>Voorkeursmoment 1</label>
                <div class="flex-row">
                    <input type="date" name="preferred_date_1">

                    <select name="preferred_time_1">
                        <option value="">Geen voorkeur</option>
                        <option value="09:00 - 12:00">09:00 - 12:00</option>
                        <option value="12:00 - 17:00">12:00 - 17:00</option>
                        <option value="17:00 - 20:00">17:00 - 20:00</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="wrapper">
            <div class="item">
                <label>Voorkeursmoment 2</label>
                <div class="flex-row">
                    <input type="date" name="preferred_date_2">

                    <select name="preferred_time_2">
                        <option value="">Geen voorkeur</option>
                        <option value="09:00 - 12:00">09:00 - 12:00</option>
                        <option value="12:00 - 17:00">12:00 - 17:00</option>
                        <option value="17:00 - 20:00">17:00 - 20:00</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="wrapper">
            <div class="item full">
                <textarea name="comments" placeholder="Bericht"></textarea>
            </div>
        </div>
        <div class="wrapper">
            <button class="submit-btn" type="submit">Versturen</button>
        </div>
    </form>

    
    <script>
    document.querySelector('.skarabee-contact-form').addEventListener('submit', function(e){
        e.preventDefault();
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: new FormData(this)
        }).then(r => r.json()).then(res => {
            console.log(res.success ? 'Verzonden!' : res.data);
        }).catch(err => {
            console.error(err);
        });
    });
    </script>

  <?php
}