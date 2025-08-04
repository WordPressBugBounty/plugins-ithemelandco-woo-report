<div class="onboarding-section">
    <div class="ithemeland-onboarding-head">
        <img src="<?php echo esc_url(ITWRL_FW_URL . 'onboarding/assets/images/iThemeland-popup-icon.png'); ?>" style="width:250px" alt="onboarding-icon">
    </div>
    <div class="ithemeland-onboarding-container">
        <div class="ithemeland-onboarding-box">
            <h2><?php echo esc_html__('Welcome To iThemeland!', 'ithemelandco-woo-report'); ?></h2>
            <p><?php echo esc_html__('It\'s great to have you here with us!', 'ithemelandco-woo-report'); ?></p>
            <form method="POST" action="">
                <?php wp_nonce_field('ithemeland_onboarding_form', '_wpnonce'); ?>
                <div class="ithemeland-onboarding-checkbox">
                    <div class="row ithemeland-pa-t-b-10">
                        <input type="checkbox" name="ithemeland_opt_in" id="ithemeland_opt_in" class="ithemeland_opt_in_checkbox" value="1" checked>
                        <label for="ithemeland_opt_in">
                            <?php echo esc_html__('Opt-in to receive tips, discounts, and recommendations from the iThemeland team directly in your inbox.', 'ithemelandco-woo-report'); ?>
                        </label>
                    </div>
                    <div class="row ithemeland-pa-t-b-10">
                        <input type="checkbox" name="ithemeland_usage_track" id="ithemeland_usage_track" class="ithemeland_usage_track" value="1" checked>
                        <label for="ithemeland_usage_track">
                            <?php echo esc_html__('I agree to share my data to tailor my store setup experience, get more relevant content, and help make the plugin better for everyone. You can opt out at any time in settings.', 'ithemelandco-woo-report'); ?>
                            <a href="<?php echo esc_url('https://ithemelandco.com/usage-tracking/?utm_source=free_plugins&utm_medium=plugin_links&utm_campaign=telemetry'); ?>">
                                <?php echo esc_html__('Learn more about usage tracking.', 'ithemelandco-woo-report'); ?>
                            </a>
                        </label>

                    </div>
                </div>
                <div class="ithemeland-onboarding-buttons">
                    <button type="button" name="ithemeland_usage_track_submit" class="ithemeland-allow-continue">
                        <?php esc_html_e('Allow & Continue', 'ithemelandco-woo-report'); ?>
                    </button>
                    <button type="button" class="ithemeland-skip"><?php esc_html_e('Skip', 'ithemelandco-woo-report'); ?></button>
                </div>
            </form>
            <div class="ithemeland-footer-note">
                <div class="ithemeland-links">
                    <a href="https://ithemelandco.com/privacy-policy/" target="_blank"><?php esc_html_e('Privacy Policy', 'ithemelandco-woo-report'); ?></a>
                    |
                    <a href="https://ithemelandco.com/term-of-use/" target="_blank"><?php esc_html_e('Terms of Service', 'ithemelandco-woo-report'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>