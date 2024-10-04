<?php $industries = ITWR_Industry_Helper::get_industries(); ?>

<div id="itwr-body">
    <div class="itwr-dashboard-body">
        <div id="itwr-activation">
            <?php if (isset($is_active) && $is_active === true && $activation_skipped !== true) : ?>
                <div class="itwr-wrap">
                    <div class="itwr-tab-middle-content">
                        <div id="itwr-activation-info">
                            <strong><?php esc_html_e("Congratulations, Your plugin is activated successfully. Let's Go!", 'iThemelandCo-Woo-Report-Lite') ?></strong>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="itwr-wrap itwr-activation-form">
                    <div class="itwr-tab-middle-content">
                        <?php if (!empty($flush_message) && is_array($flush_message)) : ?>
                            <div class="itwr-alert <?php echo ($flush_message['message'] == "Success !") ? "itwr-alert-success" : "itwr-alert-danger"; ?>">
                            <span><?php echo esc_html(sanitize_text_field($flush_message['message'])); ?></span>

                            </div>
                        <?php endif; ?>
                        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="itwr-activation-form">
                            <h3 class="itwr-activation-top-alert">Fill the below form to get the latest updates' news and <strong style="text-decoration: underline;">Special Offers(Discount)</strong>, Otherwise, Skip it!</h3>
                            <input type="hidden" name="action" value="itwr_activation_plugin">
                            <div class="itwr-activation-field">
                                <label for="itwr-activation-email"><?php esc_html_e('Email', 'iThemelandCo-Woo-Report-Lite'); ?> </label>
                                <input type="email" name="email" placeholder="Email ..." id="itwr-activation-email">
                            </div>
                            <div class="itwr-activation-field">
                                <label for="itwr-activation-industry"><?php esc_html_e('What is your industry?', 'iThemelandCo-Woo-Report-Lite'); ?> </label>
                                <select name="industry" id="itwr-activation-industry">
                                    <option value=""><?php esc_html_e('Select', 'iThemelandCo-Woo-Report-Lite'); ?></option>
                                    <?php
                                    if (!empty($industries)) :
                                        foreach ($industries as $industry_key => $industry_label) :
                                    ?>
                                            <option value="<?php echo esc_attr($industry_key); ?>"><?php echo esc_attr($industry_label); ?></option>
                                    <?php
                                        endforeach;
                                    endif
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="activation_type" id="itwr-activation-type" value="">
                            <button type="button" id="itwr-activation-activate" class="itwr-button itwr-button-lg itwr-button-blue" value="1"><?php esc_html_e('Activate', 'iThemelandCo-Woo-Report-Lite'); ?></button>
                            <button type="button" id="itwr-activation-skip" class="itwr-button itwr-button-lg itwr-button-gray" style="float: left;" value="skip"><?php esc_html_e('Skip', 'iThemelandCo-Woo-Report-Lite'); ?></button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>