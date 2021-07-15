<?php
class ModelExtensionPaymentean extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/ean');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_ean_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('payment_ean_total') > 0 && $this->config->get('payment_ean_total') > $total) {
			$status = false;
		} elseif (!$this->cart->hasShipping()) {
			$status = false;
		} elseif (!$this->config->get('payment_ean_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}
        
        $value = isset($this->session->data['ean']) ? $this->session->data['ean'] : '';

		$method_data = array();
        
        $additional_html = '
        <div id="ean-options" style="">
            <input type="text" name="ean" value="' . $value . '" placeholder="' . $this->language->get('text_placeholder') . '" class="form-control" />
            
            <script type="text/javascript">
            if ($("input[name=\"payment_method\"]:checked").val() == "ean") {
                $("#ean-options").addClass("active").show();
            } else {
                $("#ean-options").removeClass("active").hide();
            }
            
            $("input[name=\"payment_method\"]").on("click", function() {
                if ($("input[name=\"payment_method\"]:checked").val() == "ean") {
                    $("#ean-options").addClass("active").show();
                } else {
                    $("#ean-options").removeClass("active").hide();
                }
            });
            
            $("input[name=\"ean\"]").on("keyup", function() {
                $("#payment-method input[name=\"payment_method\"]:checked, #payment-method select[name=\"payment_method\"]").trigger("change");
            });
            </script>
            <style>
            #ean-options {
                display: inline-block;
                visibility: hidden;
                max-height: 15px;
                overflow: hidden;
                margin-left: -5px;
                margin-top: -5px;
                max-width: 0px;
            }
            #ean-options.active {
                display: block !important;
                visibility: visible !important;
                overflow: visible !important;
                margin-left: 0px !important;
                padding-top: 10px;
                padding-bottom: 10px;
                max-width: none !important;
                max-height: none !important;
            }
            #ean-options input {
                font-size: 12px;
                padding: 3px 6px;
                height: auto;
                width: 100%;
            }
            #ean-options input::placeholder {
                font-size: 12px;
                color: red;
            }
            </style>
        </div>';

		if ($status) {
			$method_data = array(
				'code'       => 'ean',
				'title'      => $this->language->get('text_title') . $additional_html,
				'terms'      => '',
				'sort_order' => $this->config->get('payment_ean_sort_order')
			);
		}

		return $method_data;
	}
}
