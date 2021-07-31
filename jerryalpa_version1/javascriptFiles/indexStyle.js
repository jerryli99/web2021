//////////////////////////////////////////////////////////
        //This is the header click controler: the code is from w3schools's How To section

  function openCity(name,elmnt,color) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
         tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].style.backgroundColor = "";
    }
    document.getElementById(name).style.display = "block";
    elmnt.style.backgroundColor = color;
    
    }
    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
    
    /////////////////////////////////////////////////////////////////////////////
    //Bottom to the top in javascript code: 
    //the code is from https://www.heathertovey.com/blog/floating-back-to-top-button/
    
    // var link = document.getElementById("back-to-top");
    // var amountScrolled = 250;   
    // window.addEventListener('scroll', function(e) {
    //     if (window.pageYOffset > amountScrolled) {
    //         link.classList.add('show');
    //     } else {
    //         link.className = 'back-to-top';
    //     }
    // });  
    //     //<!-- Scrolls to Top -->
    // link.addEventListener('click', function(e) {
    //     e.preventDefault();
    //     var distance = 0 - window.pageYOffset;
    //     var increments = distance/(500/16);
    //     function animateScroll() {
    //         window.scrollBy(0, increments);
    //         if (window.pageYOffset <= document.body.offsetTop) {
    //             clearInterval(runAnimation);
    //         }
    //     };
    //         // Loop the animation function
    //     var runAnimation = setInterval(animateScroll, 16);
    // });