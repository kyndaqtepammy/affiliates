    const viewBtn = document.querySelector(".sdds-gen-link"),
    popup = document.querySelector(".popup"),
    closse = popup.querySelector(".close"),
    field = popup.querySelector(".field"),
    input = document.querySelector("#sdds-affiliate-link-field"),
    copy = field.querySelector("button"),
    agree= document.querySelector('#aff-user-agree'),
    regbtn = document.querySelector("#aff-register-btn"),
    emailfield = document.querySelector("#aff-email"),
    namefield = document.querySelector("#aff-fullname"),
    fblink = document.querySelector("#aff-fb"),
    twlink = document.querySelector("#aff-tw"),
    inlink = document.querySelector("#aff-in")
    userID = script_params.user_id,
    affiliate_link = `https://supportdds.com/affiliate?sddsref-id=${userID}`;


    viewBtn.onclick = ()=>{
    	console.log(userID);
        input.value = affiliate_link;
        fblink.href = "https://www.facebook.com/sharer/sharer.php?u="+encodeURIComponent("https://supportdds.com/affiliate?sddsref-id="+userID+"&src=fb");
        fblink.target="blank"; 
        fblink.rel="noopener";

        twlink.href = "https://www.twitter.com/share?text='Placeholder Value'%0D%0A%0D%0Ahttps://supportdds.com/affiliate?sddsref-id="+userID+"&src=tw";
         twlink.target = "_blank"; 
         twlink.rel="noopener";

        inlink.href = "https://www.linkedin.com/shareArticle?mini=true&title='Placeholder Value'&url="+encodeURIComponent("https://supportdds.com/affiliate?sddsref-id="+userID+"&src=in"); 
        inlink.target="blank"; 
        inlink.rel = "noopener"; 
        inlink.onclick="window.open(this.href, 'mywin','left=20,top=20,width=500,height=500,toolbar=1,resizable=0'); return false";
        //console.log(fblink);
        popup.classList.toggle("show");
        
    }


    
    closse.onclick = ()=>{
      viewBtn.click();
    }




    copy.onclick = ()=>{
      input.select(); //select input value
      if(document.execCommand("copy")){ //if the selected text copy
        field.classList.add("active");
        copy.innerText = "Copied";
        setTimeout(()=>{
          window.getSelection().removeAllRanges(); //remove selection from document
          field.classList.remove("active");
          copy.innerText = "Copy";
        }, 3000);
      }
    }



  function doalert(elem) {
    if( elem.checked ) {
      var regbtn = document.getElementById("aff-register-btn");
      regbtn.enabled = true;
    }
  }