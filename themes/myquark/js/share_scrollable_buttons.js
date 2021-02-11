let base_div = document.getElementById("item");
let href = window.location.href;
let title = document.title;
let cntr = 0;
let btn_spacing = 0.5;
[
    // Used https://www.coderstool.com/share-social-link-generator to generate test links.
    ["fab fa-twitter", "#1DA1F2", `https://twitter.com/intent/tweet?text=Check%20out%20this%20really%20cool%20blog%20post%20by%20%40mathsppblog%3A%20${href}`, "Tweet this"],
    ["fas fa-paper-plane", "#EEEE", `mailto:%7Bemail_address%7D?subject=${title}&body=${href}`, "Email this"],
    ["fab fa-facebook-f", "#4267B2", `https://www.facebook.com/sharer/sharer.php?u=${href}`, "Share on Facebook"],
    // Set some transparency for the reddit orange so it isn't too bright.
    ["fab fa-reddit-alien", "#FF5700cc", `https://reddit.com/submit?url=${href}&title=${title}`, "Share on Reddit"],
    // ["fab fa-linkedin-in", "#0077B5", `https://www.linkedin.com/shareArticle?url=${href}`, "Share on LinkedIn"],
    // ["fab fa-hacker-news-square", "#f0652f", "https://news.ycombinator.com/submitlink?u=${href}&t=${title}", "Share on Hacker News"],
    // ["fab fa-whatsapp", "#25D366", "whatsapp://send?text=${href}", "Share via Whatsapp"],
].forEach(t => {
    let btn_div = document.createElement("div");
    btn_div.classList.add("custom-scrollable");
    btn_div.innerHTML = `<a href="${t[2]}" target="_blank" title="${t[3]}" style="text-decoration:none"><i class="${t[0]}" style="color:${t[1]}"></i></a>`;
    // Set the top of the div.
    btn_div.style.top = 6 + cntr*3 + (cntr-1)*btn_spacing + "rem";
    cntr++;

    base_div.appendChild(btn_div);
})
