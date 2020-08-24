require('../css/app.scss');

console.log('foo');
document.addEventListener('DOMContentLoaded', function () {

    function loadMessage(){
        let xhr = new XMLHttpRequest();
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                console.log('success!', xhr);
                let data = JSON.parse(xhr.responseText);
                messageBox.innerHTML = data.text;
            } else {
                console.log('The request failed!');
            }
        };

        xhr.open('GET', '/whhato');
        xhr.send();
    }


    let main = document.getElementById('message');
    main.innerHTML = '';

    let messageBox = document.createElement('span');
    main.appendChild(messageBox);

    let requestButton = document.createElement('button');
    requestButton.innerHTML = 'Neue Nachricht';
    requestButton.addEventListener("click", function (event) {
        event.preventDefault();
        loadMessage();
    });

    main.appendChild(requestButton);
    loadMessage();
});
