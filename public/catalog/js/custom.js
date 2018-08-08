$(document).ready(function() {
    $("#why-us").fancybox({
        'titleShow': true,
        'onClosed': function() {
            $("#why-us-info").hide();
        }
    });
    $('.pgwSlider').pgwSlider();

  $('.crsl-items').carousel({ overflow: false, visible: 5, itemMinWidth: 200, itemMargin: 10 });

    $("#notification").notify({
        speed: 500,
        expires: false
    });

    $(".cart-container a.shopping-cart").click(function(){
        $('.cart-box').eq(0).html("<h4>Loading...</h4>");
        $('.cart-box').eq(0).show();
        $.ajax({
            type: "POST",
            url: js_urls.cart_summary_url,
            data: { }
        }).done(function( msg ) {
            if(msg.status == 'empty'){
                $('.cart-box h4').html("Your cart is empty.");
            }
            else if(msg.status == 'contains'){
                var total = msg.products.length;
                var html = '<table class="table cart-table"><thead><tr><th colspan="2">Product</th><th>Qty</th><th>Price</th></tr></thead><tbody>';
                var total_price = 0.0;
                for (var i=0; i < total; ++i){
                    var product = msg.products[i];
                    var product_price = (parseFloat(product.order_qty) * parseFloat(product.price)).toFixed(2);
                    product.main_image = js_setting.site_url+"/"+js_setting.thumbs_dir+"/"+product.main_image;
                    html += '<tr><td><img src="'+product.main_image+'" class="cart-img" /></td><td><a href="'+js_urls.product_url+'/'+product.product_id+'">'+product.title+'</a></td><td>x'+product.order_qty+'</td><td>'+product.order_qty+'x'+product.price.toFixed(2)+'='+(product_price)+'</td></tr>';
                    total_price = parseFloat(total_price) + parseFloat(product_price);
                }
                html += '<tr><td colspan="3"><a href="'+js_urls.checkout_url+'" class="btn btn-default">Checkout</a> <a href="'+js_urls.cart_url+'" class="btn btn-default">View Cart</a></td><td>Total: '+js_setting.currency_prefix+total_price.toFixed(2)+js_setting.currency_postfix+'</td></tr>'
                html += "</tbody></table>";
                $('.cart-box').html(html);
            }
            else{
                alert("There was an error in response from server. Try again by refreshing the page.");
            }
        });
        $('.cart-container, .cart-box').mouseleave(function(){
            $('.cart-box').eq(0).hide();
        });
    });
});
function showNotification(heading, message){
    var instance = $("#notification").notify("create", {
        title: heading,
        text: message
    });
    //instance.close();
    //instance.open();

}
function showError(heading, message){
    var instance = $("#notification").notify("create", {
        title: heading,
        text: message
    });

}
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
    }
    return "";
}

function clearInputs(_obj){

    $(_obj).closest("form").find("input").each(function(){
       if(($(this).attr("type") == "password" || $(this).attr("type") == "text" ) && $(this).attr("readonly") != "readonly") {
           $(this).val("");
       }
        if($(this).attr("type") == "checkbox"){
            $(this).prop("checked", false);
        }
    });
    $(_obj).closest("form").find("select").each(function(){
        $(this).children(0).prop("selected", true);
    });
}