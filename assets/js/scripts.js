var count;
var cartdata = {};


function quantityupdate(ItemId, ItemName, ItemPrice, ItemSize, Icount) {
    // Check if the item with the given ItemId already exists in cartdata
    if (cartdata[ItemId]) {
        var existingItem = cartdata[ItemId].Itemsize[ItemSize];
    }
    var action = Icount;

    if (existingItem) {
        // Item already exists in the cart
        var itmcount = existingItem.Itemcount;
        document.getElementById('count').innerHTML = itmcount;
        count = existingItem.Itemcount;

        if (action === 'increase') {
            count += 1;
            existingItem.Itemcount = count;
            document.getElementById('count').innerHTML = count;
            cart = JSON.stringify(cartdata);
            localStorage.setItem('giveinfo', cart);
            showorder();

        } else if (action === 'decrease') {
            if (existingItem.Itemcount > 0) {
                count -= 1;
                existingItem.Itemcount = count;
                document.getElementById('count').innerHTML = count;
                if (existingItem.Itemcount == 0) {
                    var quantityAddElement = document.getElementById("quantityadd");
                    if (quantityAddElement) {
                        document.getElementById("quantityadd").style.display = "block";
                    }
                    var quantityeditElement = document.getElementById("quantityedit");
                    if (quantityeditElement) {
                        document.getElementById("quantityedit").style.display = "none";
                    }
                    delete cartdata[ItemId].Itemsize[ItemSize];
                    if (Object.keys(cartdata[ItemId].Itemsize).length === 0) {
                        delete cartdata[ItemId];
                    }
                    cart = JSON.stringify(cartdata);
                    localStorage.setItem('giveinfo', cart);
                    showorder();
                }
                cart = JSON.stringify(cartdata);
                localStorage.setItem('giveinfo', cart);
                showorder();
            }
        }
        // Update the item count
        existingItem.Itemcount = count;

    } else {
        count = 1;
        document.getElementById('count').innerHTML = count;
        Icount = count;

        if (cartdata.hasOwnProperty(ItemId)) {
            // If ItemId exists, update the existing Itemsize
            if (!cartdata[ItemId].Itemsize.hasOwnProperty(ItemSize)) {
                // If ItemSize does not exist for the ItemId, create a new one
                cartdata[ItemId].Itemsize[ItemSize] = {
                    ItemPrice: ItemPrice,
                    Itemcount: 1
                };
            }
        } else {
            // If ItemId doesn't exist, add it to cartdata
            cartdata[ItemId] = {
                ItemName: ItemName,
                Itemsize: {}
            };

            // Add the new ItemSize to the ItemId
            cartdata[ItemId].Itemsize[ItemSize] = {
                ItemPrice: ItemPrice,
                Itemcount: 1
            };
        }

        document.getElementById("quantityadd").style.display = "none";
        document.getElementById("quantityedit").style.display = "block";
        cart = JSON.stringify(cartdata);
        localStorage.setItem('giveinfo', cart);
        showorder();
    }

    // Now cartdata contains the updated items
    console.log('Updated cart data :', cartdata);
}

//   This script link category to card 
function filterItems(category) {
    $('.list-group-item').removeClass('active-li-color'); // Remove active class from all li elements
    $('li:contains("' + category + '")').addClass('active-li-color'); // Add active class to clicked li element  
    //     // localstorage
    if (localStorage.getItem("giveinfo") !== null && localStorage.getItem("giveinfo") !== '{}') {
        cartdata = JSON.parse(localStorage.getItem('giveinfo'));
    }
    cart = JSON.stringify(cartdata);
    localStorage.setItem('giveinfo', cart);
    // alert(category);
    //     // Other logic to filter items
    //     window.location.href = 'menu-card.php/?category=' + category;
    //     var items = document.querySelectorAll('.col-6');
    //     items.forEach(function (item) {
    //         if (category === 'All' || item.classList.contains(category)) {
    //             item.style.display = 'block';
    //             item.addEventListener('click', function () {
    //                 displayOffcanvasContent(item.getAttribute('data-item-id'));
    //             });
    //         } else {
    //             item.style.display = 'none';
    //         }
    //     });

    // ---------------------------------------------------------XMLHTTP AJAX -------------------------------------------------------------------

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("menu-card-id").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "menu-card.php/?category=" + category, true);
    xhttp.send();

}

//   This script is for off canvas Order
function showorder() {
    console.log("show order");

    if (localStorage.getItem("giveinfo") !== null && localStorage.getItem("giveinfo") !== '{}') {
        cartdata = JSON.parse(localStorage.getItem('giveinfo'));
    }

    var cartContainer = document.getElementById("cart-container");
    var totalAmountElement = document.getElementById("total-amount");

    // Clear existing content in the container
    cartContainer.innerHTML = '';

    var totalAmount = 0;

    for (var itemId in cartdata) {
        if (cartdata.hasOwnProperty(itemId)) {
            var item = cartdata[itemId];

            for (var size in item.Itemsize) {
                if (item.Itemsize.hasOwnProperty(size)) {

                    // Create a new row for each item
                    var row = document.createElement("div");
                    row.className = "row";

                    // Quantity and Name column
                    var col1 = document.createElement("div");
                    col1.className = "col-6 pe-0";
                    col1.innerHTML = "<p id='count'>" + item.Itemsize[size].Itemcount + " X <span id='order-name'>" + item.ItemName + "</span><span style='padding: 0px 10px 0px 10px;'>" + size + "</span></p>";
                    row.appendChild(col1);

                    // Buttons column
                    var col2 = document.createElement("div");
                    col2.className = "col-6 ps-0";
                    col2.innerHTML = "<div class='row'>" +
                        "<div class='col-3 pe-0 text-center'><div style='font-size: 28px;' onclick='quantityupdate(\"" + itemId + "\", \"" + item.ItemName + "\", \"" + item.Itemsize[size].ItemPrice + "\", \"" + size + "\", \"decrease\")'><i class='bi bi-dash-circle-fill text-success'></i></div></div>" +
                        "<div class='col-3 pe-0 ps-1 text-center'><div style='font-size: 28px;' onclick='quantityupdate(\"" + itemId + "\", \"" + item.ItemName + "\", \"" + item.Itemsize[size].ItemPrice + "\", \"" + size + "\", \"increase\")'><i class='bi bi-plus-circle-fill text-success'></i></div></div>" +
                        "<div class='col-6 ps-0'><span id='totalamount'>&#8377; " + (parseFloat(item.Itemsize[size].ItemPrice) * item.Itemsize[size].Itemcount).toFixed(2) + "</span></div>" +
                        "</div>";
                    row.appendChild(col2);

                    // Update the total amount
                    totalAmount += parseFloat(item.Itemsize[size].ItemPrice) * item.Itemsize[size].Itemcount;

                    // Append the row to the container
                    cartContainer.appendChild(row);
                }
            }
        }
    }

    if (totalAmount > 0) {
        document.getElementById("order-item-list").style.display = "block";
        document.getElementById("nothing-to-order").style.display = "none";
    } else {
        document.getElementById("nothing-to-order").style.display = "block";
        document.getElementById("order-item-list").style.display = "none";
    }

    // Display the total amount
    totalAmountElement.innerText = ' ₹ ' + totalAmount.toFixed(2) ;
    document.getElementById('upload-json').value = JSON.stringify(cartdata);
    document.getElementById('upload-total-price').value = totalAmount;
}

function customerdata() {
    if (localStorage.getItem("username") !== null) {
        var username = localStorage.getItem('username');
        document.getElementById('UserName').value = username;
    }
    if (localStorage.getItem("usernumber") !== null) {
        var usernumber = localStorage.getItem('usernumber');
        document.getElementById('UserPhone').value = usernumber;
    }
}

window.onload = () => {
    customerdata();
};