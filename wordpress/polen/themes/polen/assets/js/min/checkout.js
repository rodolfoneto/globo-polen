jQuery(document).ready((function(){jQuery("form.checkout").on("submit",(function(){blockUnblockInputs("form.checkout",!0)})),jQuery("body").on("checkout_error",(function(){blockUnblockInputs("form.checkout",!1)}))}));