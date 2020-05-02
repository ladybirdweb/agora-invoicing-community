 $(document).ready(function(){
    let ele = document.getElementById("s_key").textContent;
     let finalele = ele.match(/.{1,4}/g).join('-');
        document.getElementById("s_key").textContent = finalele;
})

document.querySelectorAll('span[data-type="copy"]')
    .forEach(function(button){
      button.addEventListener('click', function(){
        let serialKey = this.parentNode.parentNode.querySelector('td[data-type="serialkey"]').innerText;

      let tmp = document.createElement('textarea');
      tmp.value= serialKey.replace(/\-/g, '');
      tmp.setAttribute('readonly', '');
      tmp.style.position = 'absolute';
      tmp.style.left = '-9999px';
      document.body.appendChild(tmp);
      tmp.select();
      document.execCommand('copy');
      document.body.removeChild(tmp);
      $("#copied").css("display", "block");
      
      $('#copied').fadeIn("slow","swing");
      $('#copied').fadeOut("slow","swing");
      })
    })