const boules = 'https://www.zeus2025.be/exe/boutique.xml';
let cart = 0;
let somme = 0;
let balls = [];
let collectionOfArticles;
let ajouter;;

function addToCart(index) {
    let currentBall = document.getElementById("ball" + index);
    let prix = Number.parseFloat(currentBall.querySelector(".prixBoule").textContent);
    let stock = Number.parseInt(-- currentBall.querySelector(".stockBoule").textContent);
    console.log(stock);
    cart += 1;
    somme += prix;
    let cartTotal = document.getElementById("cartTotal");
    cartTotal.innerText = cart;
    let cartSomme = document.getElementById("cartSomme");
    cartSomme.innerText = somme;
    if (stock === 0) {
        currentBall.querySelector(".boutonAjout").disabled = true;
        currentBall.querySelector(".boutonAjout").textContent = "Article non disponible";
    }
}

function domCreate () {
    readBoules();
    //console.log(balls);
    collectionOfArticles = document.getElementById('articles');
/*    collectionOfArticles.length = 0;
    collectionOfArticles.selectedIndex = 0;*/
    let boule;
    for (let i = 0; i < balls.length; i++) {
        boule = document.createElement('div');
        boule.setAttribute("class", "article");
        boule.setAttribute("id", "ball" + i);
        collectionOfArticles.append(boule);

        let itemId = document.createElement('p');
        itemId.setAttribute("class", "idBoule");
        itemId.setAttribute("hidden", "true");
        let itemLib = document.createElement('p');
        itemLib.setAttribute("class", "libBoule");
        let itemPrix = document.createElement('p');
        itemPrix.setAttribute("class", "prixBoule");
        let itemStock = document.createElement('p');
        itemStock.setAttribute("class", "stockBoule");
        itemStock.setAttribute("hidden", "true");
        let itemImage = document.createElement('img');
        itemImage.setAttribute("class", "imageBoule");
        ajouter = document.createElement('button');
        ajouter.setAttribute("class","boutonAjout");

        boule.append(itemImage);
        boule.append(itemId);
        boule.append(itemLib);
        boule.append("Bla bla bla bla Bla bla bla bla");
        boule.append(itemPrix);
        boule.append(itemStock);
        boule.append(ajouter);

        itemImage.src = balls[i].image;
        itemLib.innerHTML = balls[i].lib;
        itemPrix.innerHTML = balls[i].prix;
        itemId.innerHTML = balls[i].idd;
        itemStock.innerHTML = balls[i].stock;

        if (itemStock.innerText > 0) {
            ajouter.disabled = false;
            ajouter.onclick = function() { addToCart(i); };
            ajouter.textContent = "Ajouter au panier";
        } else {
            ajouter.disabled = true;
            ajouter.textContent = "Article non disponible";
        }
    }
    let champsPrix = document.querySelectorAll(".prixBoule");
    champsPrix.forEach( x => x.innerHTML += " €");
}

function readBoules() {
    if (window.XMLHttpRequest) {
        xmlHttp = new XMLHttpRequest();
    } else { // POUR INTERNET EXPLORER
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlHttp.open("GET", boules, false);
    xmlHttp.send();
    xmlDoc = xmlHttp.responseXML;
    let countArticles = xmlDoc.getElementsByTagName('article').length;
    let oneBall;
    let idd, lib, prix, stock, image;
    for (let i = 0; i < countArticles; i++) {
        idd = xmlDoc.getElementsByTagName("id")[i].childNodes[0].nodeValue;
        lib = xmlDoc.getElementsByTagName("lib")[i].childNodes[0].nodeValue;
        prix = xmlDoc.getElementsByTagName("prix")[i].childNodes[0].nodeValue;
        stock = xmlDoc.getElementsByTagName("stock")[i].childNodes[0].nodeValue;
        image = xmlDoc.getElementsByTagName("image")[i].childNodes[0].nodeValue;
        oneBall = {
            idd,
            lib,
            prix,
            stock,
            image
        }
        balls[i] = oneBall;
    }
    //console.log(balls);
}

