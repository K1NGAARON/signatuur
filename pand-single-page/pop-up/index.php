<?php

function get_contact_form( $pub_id ) {
  ?>
  <form method="post" class="skarabee-contact-form booking-form-wrapper">
    <div class="wrapper">
      <input type="hidden" name="action" value="skarabee_contact">
      <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('skarabee_contact_nonce'); ?>">
      <input type="hidden" name="publication_id" value="<?php echo esc_attr($pub_id); ?>">
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
            <?php
              // Uur per uur: 09:00 - 20:00 (laatste slot: 19:00 - 20:00)
              for ($h = 9; $h < 20; $h++) {
                $start = sprintf('%02d:00', $h);
                $end   = sprintf('%02d:00', $h + 1);
                $label = $start . ' - ' . $end;
                echo '<option value="'. esc_attr($label) .'">'. esc_html($label) .'</option>';
              }
            ?>
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
            <?php
              for ($h = 9; $h < 20; $h++) {
                $start = sprintf('%02d:00', $h);
                $end   = sprintf('%02d:00', $h + 1);
                $label = $start . ' - ' . $end;
                echo '<option value="'. esc_attr($label) .'">'. esc_html($label) .'</option>';
              }
            ?>
          </select>
        </div>
      </div>
    </div>

    <div class="wrapper">
      <div class="item full">
        <textarea name="comments" placeholder="Bericht"></textarea>
      </div>
    </div>

    <!-- Success message (hidden by default) -->
    <div class="wrapper">
      <p class="skarabee-contact-success" hidden>Aanvraag goed verstuurd!</p>
    </div>

    <div class="wrapper">
      <button class="submit-btn" type="submit">Versturen</button>
    </div>
  </form>

  <script>
    (function () {
      const form = document.querySelector('.skarabee-contact-form');
      if (!form) return;

      const successEl = form.querySelector('.skarabee-contact-success');

      form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Hide success message on re-submit
        if (successEl) successEl.hidden = true;

        fetch('<?php echo esc_url( admin_url('admin-ajax.php') ); ?>', {
          method: 'POST',
          body: new FormData(form)
        })
        .then(r => r.json())
        .then(res => {
          if (res && res.success) {
            if (successEl) successEl.hidden = false; // show message
            form.reset();
          } else {
            console.log(res?.data || 'Er ging iets mis.');
          }
        })
        .catch(err => console.error(err));
      });
    })();
  </script>

  <?php
}