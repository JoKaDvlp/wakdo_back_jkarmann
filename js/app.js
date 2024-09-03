const btnsReadyStatus = document.querySelectorAll(".btn-ready-status");

btnsReadyStatus.forEach(btn => {
    btn.addEventListener("click", ()=>{
        fetch(`${btn.dataset.url}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
        })
        .then(location.reload())
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    })
});

const btnsViewDetails = document.querySelectorAll(".btn-view-details");

btnsViewDetails.forEach(btn => {
    btn.addEventListener("click", ()=>{
        fetch(`${btn.dataset.url}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            buildDetailsTemplate(data)
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
    })
})

function buildDetailsTemplate(data){
    let html = "";
    
    data.map(product => {
        if (product.category === "menus") {
            html += `
            <div>
                <p>${product.quantity} Menu ${product.menu_size} ${product.product_name}</p>
                <ul>
                    <li>${product.side_name}</li>
                    <li>${product.beverage_name}</li>
                    <li>Sauce ${product.sauce_name}</li>
                </ul>
            </div>
            `
        } else if (product.category === "boissons"){
            html += `
            <div>
                <p>${product.quantity} ${product.product_name}</p>
                <ul>
                    <li>${product.beverage_size}</li>
                </ul>
            </div>
        `
        } else {
            html +=`
            <div>
                <p>${product.quantity} ${product.product_name}</p>
            </div>
            `
        }
    });
    document.querySelector(".order-detail").innerHTML = html;
}