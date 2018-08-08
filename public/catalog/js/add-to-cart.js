$(document).ready(function(){

    $("#add_to_cart_btn").click(function(){

        var product_option = "";
        if($("#product_option").length){
            product_option = $(".product_option").eq(0).val();
        }
        else{
            product_option = product_options_title[0];
        }
        var order_qty = parseInt($("#order_qty").val());
        var product_id = $("#product_id").val();


        if(isNaN(order_qty) || order_qty <= 0){
            showError("Invalid Order Quantity", "Order quantity must be 1 or more. Try again");
            $("#order_qty").focus();
            return false;
        }
        var opt_index = 0;
        if($("#product_option option").length > 1){
            opt_index = $("#product_option option:selected").index();
        }

        if(product_options_qty[opt_index] == 0 && ($("#product_option").prop("tagName") == 'select' || $("#product_option").prop("tagName") == 'SELECT')){
            showError("Error","Selected product option is out of stock.");
            $("#order_qty").focus();
            return false;
        }
        if(product_options_qty[opt_index] == 0 && ($("#product_option").prop("tagName") == 'input' || $("#product_option").prop("tagName") == 'INPUT')){
            showError("Error","This product is out of stock.");
            $("#order_qty").focus();
            return false;
        }
        if(product_options_qty[opt_index] != -1 && order_qty > product_options_qty[opt_index]){
            showError("Error","Your order quantity is more than available quantity for this product option. Try again.");
            $("#order_qty").focus();
            return false;
        }
        if(product_options_qty[opt_index] == 0){
            showError("Error","This product option is out of stock. Try again.");
            $("#order_qty").focus();
            return false;
        }
        var cbtn = $(this);
        $(this).attr('disabled', true);
        var _colour = '';
        if($("#colour").length){
            _colour = $("#colour").val();
        }
        $.ajax({
            type: "POST",
            url: js_urls.add_to_cart_url,
            data: { product_id: product_id , option: product_option, qty:order_qty, colour:_colour}
        }).done(function( msg ) {

            showNotification("Success", "Product added to cart successfully");
            cbtn.removeAttr("disabled");


            var total_qty = parseInt($("#cart-qty").text()) + 1;
            var qty_text = "item";
            if(total_qty > 1){
                qty_text = "items";
            }
            var remove_price = 0;
            if(typeof(msg.updatedFrom) == 'undefined'){
                $("#cart-qty").html(total_qty);
                $("#cart-qty-text").html(qty_text);
            }
            else{
                remove_price = parseInt(msg.updatedFrom) * parseFloat($(".unit-price").eq(0).text());
            }
            var total_price = parseFloat($("#cart-price").text()) + (order_qty * parseFloat($(".unit-price").eq(0).text())) - remove_price;

            $("#cart-price").html(total_price.toFixed(2));
        }).fail(function( msg ) {
            alert( "There was a problem while contacting server.\nPlease refresh the page and  try again." );
            cbtn.removeAttr("disabled");
        });

    });

    if($("#product_option option").length > 1){
        $("#product_option").change(function(){

            var stock = "Out of stock";
            var opt_index = $("#product_option option:selected").index();
            var avail_qty = parseInt(product_options_qty[opt_index]);
            if(avail_qty == -1){
                stock = "In stock";
            }
            else if (avail_qty != 0){
                stock = avail_qty;
            }
            $(".available_qty").eq(0).html(stock);
        });
    }




});