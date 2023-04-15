document.addEventListener("DOMContentLoaded", function() {
    const codeBlocks = document.querySelectorAll("pre code");
    const copyIcon = `<i class="far fa-copy fa-lg clipboard-icon"></i>`;
    
    codeBlocks.forEach((block) => {
        const wrapper = document.createElement("div");
        wrapper.style.position = "relative";
        block.parentNode.insertBefore(wrapper, block);
        wrapper.appendChild(block);
        wrapper.insertAdjacentHTML("beforeend", copyIcon);
        const icon = wrapper.querySelector(".fa-copy");
    
        icon.addEventListener("click", () => {
            const range = document.createRange();
            range.selectNodeContents(block);
            navigator.clipboard.writeText(block.textContent);
            /*
            const tempText = document.createElement("textarea");
            document.body.appendChild(tempText);
            tempText.value = block.textContent;
            tempText.select();
            document.execCommand("copy");
            tempText.remove();
            */
    
            icon.classList.replace("fa-copy", "fa-check");
            setTimeout(() => {
                icon.classList.replace("fa-check", "fa-copy");
            }, 2000);
        });
    
        icon.addEventListener("mouseover", () => {
            const tooltip = document.createElement("p");
            tooltip.textContent = "Copy to clipboard";
            tooltip.style.position = "absolute";
            tooltip.style.top = "30px";
            tooltip.style.right = "0";
            tooltip.style.padding = "3px";
            tooltip.style.border = "1px solid #f8f8f2";
            tooltip.style.borderRadius = "6px";
            tooltip.style.fontSize = "75%";
            wrapper.appendChild(tooltip);
    
            icon.addEventListener("mouseout", () => {
                tooltip.remove();
            });
    
            icon.addEventListener("click", () => {
                tooltip.textContent = "Copied!";
                setTimeout(() => {
                    tooltip.textContent = "Copy to clipboard";
                }, 2000);
            });
        });
    });
});
