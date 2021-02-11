let base_div = document.getElementById("item");
let cntr = 0;
let btn_spacing = 1;
[
    ["fas fa-pizza-slice", "https://buymeacoffee.com/mathspp", "Buy me a pizza slice!"],
    ["fab fa-facebook", "https://facebook.com/mathspp", "Like this page on facebook."]
].forEach(t => {
    let btn_div = document.createElement("div");
    btn_div.classList.add("custom-scrollable");
    btn_div.innerHTML = `<a href="${t[1]}" target="_blank"><i class="${t[0]}" style="font-size:2rem;"></i></a>`;
    btn_div.style.minWidth = "3rem";
    btn_div.style.maxWidth = "300px";
    let other_div = document.createElement("div");
    other_div.classList.add("custom-scrollable");
    //other_div.style.width = "0";
    //other_div.style.maxWidth = "300px";
    //other_div.style.width = "auto";
    //other_div.style.paddingLeft = "1rem";
    //other_div.style.paddingRight = "1rem";
    //other_div.style.display = "none";
    //other_div.style.transition = "all 2s ease";

    // Set the top of both divs.
    btn_div.style.top = 6 + cntr*3 + (cntr-1)*btn_spacing + "rem";
    //other_div.style.top = 6 + cntr*3 + (cntr-1)*btn_spacing + "rem";
    cntr++;

    let text = document.createElement("p");
    text.innerHTML = t[2];
    text.style.display = "inline";
    text.style.transition = "all 2s ease";
    text.style.margin = "0";
    btn_div.appendChild(text);
    //other_div.appendChild(text);

    btn_div.onmouseenter = function() {
        console.log("entering");
        //other_div.style.width = "100%";
        btn_div.style.width = btn_div.style.width = "100%";
        text.style.display = "block";
        //other_div.style.display = "flex";
    }
    btn_div.onmouseleave = function() {
        console.log("leaving");
        //other_div.style.width = "0%";
        //other_div.style.display = "none";
        btn_div.style.width = btn_div.style.minWidth;
        text.style.display = "none";
    }
    base_div.appendChild(btn_div);
    base_div.appendChild(other_div);
})
