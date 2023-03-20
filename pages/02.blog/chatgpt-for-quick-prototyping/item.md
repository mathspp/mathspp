Learn how to use ChatGPT to quickly test and prototype programs.

===


<script>
(function (window, document) {
    'use strict';
    
    let popupTimer = null;
    const popupDelay = 300;
    const shareButtonsHTML = `
    <div id="text-selection-sharer">
        <button class="share-button" data-platform="twitter"><i class="fa fa-twitter"></i> Tweet</button>
        <button class="share-button" data-platform="facebook"><i class="fa fa-facebook"></i> Share on Facebook</button>
        <button class="share-button" data-platform="linkedin"><i class="fa fa-linkedin"></i> Share on LinkedIn</button>
    </div>`;
    
    document.body.insertAdjacentHTML('beforeend', shareButtonsHTML);
    
    const sharePopup = document.getElementById('text-selection-sharer');
    
    const shareUrls = {
        twitter: (url, text) => `https://twitter.com/share?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`,
        facebook: (url) => `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,
        linkedin: (url) => `https://www.linkedin.com/shareArticle?mini=true&url=${encodeURIComponent(url)}`
    };
    
    function positionPopup(selection) {
        const rect = selection.getRangeAt(0).getBoundingClientRect();
        sharePopup.style.left = `${rect.left}px`;
        sharePopup.style.top = `${rect.top - 20}px`;
    }
    
    function showPopup() {
        sharePopup.classList.add('visible');
    }
    
    function hidePopup() {
        if (popupTimer) {
            clearTimeout(popupTimer);
            popupTimer = null;
        }
        sharePopup.classList.remove('visible');
    }
    
    function onSelection(e) {
        const selection = window.getSelection();
        const selection_text = selection.toString().trim();
        if (selection_text.length > 0) {
            positionPopup(selection);
            showPopup();
            setTextToBeShared(selection_text);
        } else {
            if (!popupTimer) {
                popupTimer = setTimeout(hidePopup, popupDelay);
            }
        }
    }
    
    function setTextToBeShared(selectedText) {
        const currentUrl = window.location.href;

        document.querySelectorAll('.share-button').forEach(button => {
            const platform = button.getAttribute('data-platform');
            const shareUrl = platform === 'twitter' ? shareUrls[platform](currentUrl, selectedText) : shareUrls[platform](currentUrl);
            button.setAttribute('onclick', `window.open('${shareUrl}', '_blank', 'noopener')`);
        });
    }

    // Event listeners
    document.addEventListener('mouseup', onSelection);
    document.addEventListener('touchend', onSelection);
    document.addEventListener('mousedown', () => {
        if (popupTimer) {
            clearTimeout(popupTimer);
            popupTimer = null;
        }
    });

})(window, document);
</script>

<style>
#text-selection-sharer {
    position: absolute;
    display: none;
    padding: 5px;
    background-color: #f1f1f1;
    border: 1px solid #c1c1c1;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    z-index: 1000;
}
#text-selection-sharer.visible {
    display: flex;
}
.share-button {
    cursor: pointer;
    background: none;
    border: none;
    font-size: 14px;
    padding: 5px 10px;
    color: #333;
}
.share-button:hover {
    background-color: #f9f9f9;
}
</style>


# Creating quick prototypes

[ChatGPT] is a large language model developed by OpenAI that has taken the world by storm.
I have been playing around with ChatGPT a lot and I can say, confidently, that it has made me much more productive.

As an example, go ahead and select that previous sentence.

[ChatGPT]: https://chat.openai.com
