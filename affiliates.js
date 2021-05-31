    const viewBtn = document.querySelector(".sdds-gen-link"),
    popup = document.querySelector(".popup"),
    close = popup.querySelector(".close"),
    field = popup.querySelector(".field"),
    input = document.querySelector("#sdds-affiliate-link-field"),
    copy = field.querySelector("button"),
    emailfield = document.querySelector("#aff-email"),
    namefield = document.querySelector("#aff-fullname"),
    fblink = document.querySelector("#aff-fb"),
    twlink = document.querySelector("#aff-tw"),
    inlink = document.querySelector("#aff-in")
    userID = script_params.user_id,
    affiliate_link = `http://localhost:8083/testing/affiliate?sddsref-id=${userID}`
    ;


    viewBtn.onclick = ()=>{
    	console.log(userID);
        input.value = affiliate_link;
        fblink.href = `https://www.facebook.com/sharer/sharer.php?u=${affiliate_link}&src=fb`;
        fblink.target="blank"; 
        fblink.rel="noopener";

        twlink.href = `https://www.twitter.com/share?text=${"Placeholder Value"}%0D%0A%0D%0A${affiliate_link}&src=tw`;
         twlink.target = "blank"; 
         twlink.rel="noopener";

        inlink.href = `https://www.linkedin.com/shareArticle?mini=true&title=${"Placeholder Value"}&url=${affiliate_link}&src=in`; 
        inlink.target="blank"; 
        inlink.rel = "noopener"; 
        inlink.onclick="window.open(this.href, 'mywin','left=20,top=20,width=500,height=500,toolbar=1,resizable=0'); return false";
console.log(fblink);
        popup.classList.toggle("show");
        
    }
    
    close.onclick = ()=>{
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


