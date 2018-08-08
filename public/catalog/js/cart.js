var cart_products = {};
var cart_products = {};
$(document).ready(function(){

        $.ajax({
            type: "POST",
            url: js_urls.cart_summary_url,
            data: { }
        }).done(function( msg ) {

            if(msg.status == 'empty'){
                $('.cart-page h4').html("Your cart is empty. "+'<a href="'+js_setting.site_url+'" >Click here</a> to browse products.');
            }
            else if(msg.status == 'contains'){
                cart_products = msg.products;
                var total = msg.products.length;
                var html = '<table class="table cart-table"><thead><tr><th colspan="2">Product</th><th>Qty & Option</th><th>Price</th><th>Action</th></tr></thead><tbody>';
                var total_price = 0.0;
                for (var i=0; i < total; ++i){
                    var product = msg.products[i];
                    var product_price = (parseFloat(product.order_qty) * parseFloat(product.price)).toFixed(2);
                    var options = '<input type="hidden" class="product_option" value="'+product.option.available_qty+'">';
                    var qty_style = "max-width:300px;";
                    if(product.options.length > 1){
                        options = '<div class="input-group" style="max-width:170px; float:right;"><span class="input-group-addon">Option: </span><select class="form-control product_option" >';
                        for(var j=0; j< product.options.length; ++j ){
                            if(product.options[j].available_qty == 0){
                                continue;
                            }
                            var selected = "";
                            if(product.options[j].option_title == product.option.title){
                                selected = "selected";

                            }
                            options += '<option value="'+product.options[j].available_qty+'" '+selected+'>'+product.options[j].option_title+'</option>';
                        }
                        options += '</select></div>';
                        qty_style = 'max-width:100px; float:left;';
                    }

                    product.main_image = js_setting.site_url+"/"+js_setting.thumbs_dir+"/"+product.main_image;

                    var qty_html = '<div class="input-group" style="'+qty_style+'">'+
                        '<span class="input-group-btn">'+
                        '<span class="hidden-xs"><button type="button" class="qty-minus btn btn-default">-</button>'+
                        '</span></span>'+
                        '<input type="text" value="'+product.order_qty+'" class="form-control order_qty" name="order_qty">'+
                        '<span class="input-group-btn">'+
                        '<span class="hidden-xs"><button type="button" class="qty-plus btn btn-default">+</button>'+
                        '</span></span>'+
                        '</div>';

                    html += '<tr><td><span class="hidden-xs"><img src="'+product.main_image+'" class="cart-img" /><span/></td><td><a href="'+js_urls.product_url+'/'+product.product_id+'">'+product.title+'</a></td><td style="width:300px;">'+options +''+qty_html+'</td><td><span class="hidden-xs">'+product.order_qty+'x'+product.price.toFixed(2)+'=</span>'+(product_price)+'</td>'+
                        '<td><a href="javascript:;" class="btn btn-default" onclick="updateCart('+product.product_id+', '+i+', this);" >Update</a> <img style="width:27px;height:27px;cursor:pointer;" src="'+js_urls.remove_img_url+'" title="Remove from Cart" onclick="removeFromCart('+product.product_id+', '+i+', this);" /> </td></tr>';
                    total_price = parseFloat(total_price) + parseFloat(product_price);
                }
                html += '<tr><td colspan="3"></td><td>Total: '+js_setting.currency_prefix+total_price.toFixed(2)+js_setting.currency_postfix+' </td><td><a href="'+js_urls.checkout_url+'" class="btn btn-default">Checkout</a></td></tr>';
                html += "</tbody></table>";
                $('.cart-page h4').replaceWith(html);


                $(".qty-plus").click(function(){
                    var ind = $(".qty-plus").index(this);
                    var order_qty = parseInt($(".order_qty").eq(ind).val());
                    if(isNaN(order_qty) || order_qty <= 0){
                        $(".order_qty").eq(ind).val(1);
                    }
                    else{
                        $(".order_qty").eq(ind).val(++order_qty);
                    }
                });
                $(".qty-minus").click(function(){
                    var ind = $(".qty-minus").index(this);
                    var order_qty = parseInt($(".order_qty").eq(ind).val());
                    if(isNaN(order_qty) || order_qty <= 1){
                        $(".order_qty").eq(ind).val(1);
                    }
                    else{
                        $(".order_qty").eq(ind).val(--order_qty);
                    }
                });
            }
            else{
                alert("There was an error in response from server. Try again by refreshing the page.");
            }
        });
        $('.cart-container, .cart-box').mouseleave(function(){
            $('.cart-box').eq(0).hide();
        });

});
function updateCart(pid, index, btn){

    var order_qty = parseInt($(".order_qty").eq(index).val());
    if(isNaN(order_qty)){
        showError('Error', 'Inserted quantity of product "'+cart_products[index].title+'" is not correct. Please fix it and try again.');
        return false;
    }
    else if(order_qty == 0){
        showError('Error', 'Inserted quantity of product "'+cart_products[index].title+'" cannot be 0. Please fix it and try again.');
        return false;
    }
    else if(order_qty > parseInt($(".product_option").eq(index).val()) && parseInt($(".product_option").eq(index).val()) != -1){
        showError('Error', 'Inserted quantity of product "'+cart_products[index].title+'" is more than available. Available quantity is '+parseInt($(".product_option").eq(index).val())+'. Please fix it and try again.');
        return false;
    }
    else{
        $(btn).attr('disabled', true);
        var product_option = cart_products[index].option.title;
        if($(".product_option").eq(index).prop("tagName") == 'select'){
            var product_option = $(".product_option").eq(index).children(":selected").text();
        }

        $.ajax({
            type: "POST",
            url: js_urls.add_to_cart_url,
            data: { product_id: cart_products[index].product_id , option: product_option, qty:order_qty}
        }).done(function( msg ) {

            window.location = js_urls.cart_url +  "?update=success";

        }).fail(function( msg ) {
            alert( "There was a problem while contacting server.\nPlease refresh the page and  try again." );
            $(btn).removeAttr("disabled");
        });
    }
}

function removeFromCart(pid, index, btn){
    $(btn).attr('disabled', true);
    var product_option = cart_products[index].option.title;
    if($(".product_option").eq(index).prop("tagName") == 'select'){
        var product_option = $(".product_option").eq(index).children(":selected").text();
    }
    $.ajax({
        type: "POST",
        url: js_urls.add_to_cart_url,
        data: { product_id: cart_products[index].product_id , option: product_option, qty:0}
    }).done(function( msg ) {

        window.location = js_urls.cart_url +  "?update=success";

    }).fail(function( msg ) {
        alert( "There was a problem while contacting server.\nPlease refresh the page and  try again." );
        $(btn).removeAttr("disabled");
    });
}