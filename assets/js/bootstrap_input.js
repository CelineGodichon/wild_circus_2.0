const imageInputs = document.getElementsByClassName('custom-file-input');
for (let i = 0; i < imageInputs.length; i ++) {
    imageInputs[i].addEventListener('change', function (e) {
        const fileName = imageInputs[i].files[0].name;
        const nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
}