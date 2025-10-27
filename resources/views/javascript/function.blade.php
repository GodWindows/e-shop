<script>

    refreshCartCount();

    // Function to create the cookie 
    function saveCartInCookie(value, days) {
        let expires;
        name = 'cart';
        //on convertit la Map 'value' en string  pour la stocker
        var value = JSON.stringify(Array.from(value.entries()));

        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        }
        else {
            expires = "";
        }

        document.cookie = escape(name) + "=" +
            escape(value) + expires + "; path=/";
    }

    // Function to create the cookie 
    function getCartFromCookie() {
        name = "cart";
        var escapeName = escape(name) + "=";
        const cookieValue = document.cookie
        .split("; ")
        .find((row) => row.startsWith(escapeName))
        ?.split("=")[1];
        var value = unescape(cookieValue);
        return value ? new Map(JSON.parse(value)) : new Map([]);
    }

    function refreshCartCount() {
        var cart = getCartFromCookie();
        var itemsCount = 0;
        for (var [key, value] of cart) {
            itemsCount++;
        }
        var itemsCountTag = document.querySelector("#cartItemsCount");
        itemsCountTag.innerHTML = itemsCount;
    }
    function addToCart(productId) {
        productAmount = Number(document.querySelector('#itemCount').value);
         
        //on récupère le panier
        var cart = getCartFromCookie();
        console.log([...cart]);
        var productCount = Number(cart.get(productId)) ?? 0;
        var newAmount = productCount + productAmount;
        cart.set(productId, newAmount);
        saveCartInCookie(cart, 7);
        console.log("addToCart called");
        console.log(cart);
        
        refreshCartCount();
        
    }
</script>