

    
    






function remove_Register_error(){
    document.getElementById("msgError").style.display = "none"
    document.getElementById("msgError2").style.display = "none"
}
   
    
    function RegShow(input){
        if(input=='enable')
        document.getElementById("school_popup").style.display = "block"
        else if(input=='disable')
        document.getElementById("school_popup").style.display = "none"
    }
    
    function hideBoxes(){

       if(document.getElementById("box1").style.display=='none'){
            $("#box1").show(500);
            $("#box2").show(500);
            $("#box3").show(500);
            $("#box4").hide(500);
            $("#box5").hide(500);
            $("#box6").hide(500);
        
       }
       else{
            $("#box1").hide(500);
            $("#box2").hide(500);
            $("#box3").hide(500);
            $("#box4").show(500);
            $("#box5").show(500);
            $("#box6").show(500);
            
        
       }


    }
    var id1=''
    
    
    function reg_def_onfocus(id){
        if(document.getElementById(id+"_error").style.display == "block"){
          document.getElementById(id+"_error").style.display = "none"  
          document.getElementById(id+"_def").style.display = "block"
          id1 = id  
        }
        else{
          document.getElementById(id+"_def").style.display = "block"
          id1 = id  
        }
        
    }
    
    
    function reg_def_blur(){
        document.getElementById(id1+"_def").style.display = "none"
        id1 = ''
       
    }
    
    function validation(part){
        var schoolName = document.forms['form']['schoolName'].value
        var selectSchoolType = document.forms['form']['selectSchoolType'].value
        var selectClassYear = document.forms['form']['selectClassYear'].value
        var bus = document.forms['form']['bus'].value
       
        var error=0
         if(schoolName==''){ 
          document.getElementById("schoolName_error").innerHTML='you must complete this field' 
          document.getElementById("schoolName_error").style.display='block' 
          error = 1
         }
         
         if(selectSchoolType==''){ 
          document.getElementById("selectSchoolType_error").innerHTML='you must complete this field' 
          document.getElementById("selectSchoolType_error").style.display='block' 
          error = 1
         }
         
         if(selectClassYear==''){ 
          document.getElementById("selectClassYear_error").innerHTML='you must complete this field' 
          document.getElementById("selectClassYear_error").style.display='block' 
          error = 1
         }

         
         else if(error == 0){
            if(part==2){
              document.getElementById("school_popup1").style.display = 'none'
            document.getElementById("school_popup2").style.display = 'block'  
            document.getElementById("school_popup3").style.display = 'none'
            }
            else{
            document.getElementById("school_popup1").style.display = 'none'
            document.getElementById("school_popup2").style.display = 'none'
            document.getElementById("school_popup3").style.display = 'block'
            }
            
         }
         
         window.scroll(0,0)
         
     
         
    }



function validation_2(part){
        var email = document.forms['form']['email'].value
        var firstName = document.forms['form']['firstName'].value
        var secondName = document.forms['form']['secondName'].value
        var lName = document.forms['form']['lName'].value
        var birthD = document.forms['form']['birthD'].value
        var address = document.forms['form']['address'].value
        var homePhone = document.forms['form']['homePhone'].value
        var mobPhone = document.forms['form']['mobPhone'].value
        
       
        var error=0
         if(email==''){ 
          document.getElementById("email_error").innerHTML='you must complete this field' 
          document.getElementById("email_error").style.display='block' 
          error = 1
         }
         
         if(firstName==''){ 
          document.getElementById("firstName_error").innerHTML='you must complete this field' 
          document.getElementById("firstName_error").style.display='block' 
          error = 1
         }
         
         if(secondName==''){ 
          document.getElementById("secondName_error").innerHTML='you must complete this field' 
          document.getElementById("secondName_error").style.display='block' 
          error = 1
         }
         if(lName==''){ 
          document.getElementById("lName_error").innerHTML='you must complete this field' 
          document.getElementById("lName_error").style.display='block' 
          error = 1
         }
         if(birthD==''){ 
          document.getElementById("birthD_error").innerHTML='you must complete this field' 
          document.getElementById("birthD_error").style.display='block' 
          error = 1
         }
         if(address==''){ 
          document.getElementById("address_error").innerHTML='you must complete this field' 
          document.getElementById("address_error").style.display='block' 
          error = 1
         }
         if(homePhone==''){ 
          document.getElementById("homePhone_error").innerHTML='you must complete this field' 
          document.getElementById("homePhone_error").style.display='block' 
          error = 1
         }
         if(mobPhone==''){ 
          document.getElementById("mobPhone_error").innerHTML='you must complete this field' 
          document.getElementById("mobPhone_error").style.display='block' 
          error = 1
         }

         
         else if(error == 0){
            if(part==3){
              document.getElementById("school_popup1").style.display = 'none'
            document.getElementById("school_popup3").style.display = 'block'  
            document.getElementById("school_popup2").style.display = 'none'
            }
            else{
            document.getElementById("school_popup1").style.display = 'none'
            document.getElementById("school_popup3").style.display = 'none'
            document.getElementById("school_popup2").style.display = 'block'
            }
            
         }
         
         window.scroll(0,0)
         
     
         
    }

    
 
 function validation_3(part){
        var fatherName = document.forms['form']['fatherName'].value
        var fatherEmail = document.forms['form']['fatherEmail'].value
        var fatherJob = document.forms['form']['fatherJob'].value
        var fatherMobNum = document.forms['form']['fatherMobNum'].value
        var fatherCertf = document.forms['form']['fatherCertf'].value
        var faterCertfP = document.forms['form']['fatherCertfP'].value
        var fatherCardNum = document.forms['form']['fatherCardNum'].value
        var fatherCardP = document.forms['form']['fatherCardP'].value
        
       
        var error=0
         if(fatherName==''){ 
          document.getElementById("fatherName_error").innerHTML='you must complete this field' 
          document.getElementById("fatherName_error").style.display='block' 
          error = 1
         }
         
         if(fatherEmail==''){ 
          document.getElementById("fatherEmail_error").innerHTML='you must complete this field' 
          document.getElementById("fatherEmail_error").style.display='block' 
          error = 1
         }
         
         if(fatherJob==''){ 
          document.getElementById("fatherJob_error").innerHTML='you must complete this field' 
          document.getElementById("fatherJob_error").style.display='block' 
          error = 1
         }
         if(fatherMobNum==''){ 
          document.getElementById("fatherMobNum_error").innerHTML='you must complete this field' 
          document.getElementById("fatherMobNum_error").style.display='block' 
          error = 1
         }
         
         if(fatherCertf==''){ 
          document.getElementById("fatherCertf_error").innerHTML='you must complete this field' 
          document.getElementById("fatherCertf_error").style.display='block' 
          error = 1
         }
         if(faterCertfP==''){ 
          document.getElementById("faterCertfP_error").innerHTML='complete this field' 
          document.getElementById("faterCertfP_error").style.display='block' 
          error = 1
         }
         if(fatherCardNum==''){ 
          document.getElementById("fatherCardNum_error").innerHTML='you must complete this field' 
          document.getElementById("fatherCardNum_error").style.display='block' 
          error = 1
         }
         if(fatherCardP==''){ 
          document.getElementById("fatherCardP_error").innerHTML='complete this field' 
          document.getElementById("fatherCardP_error").style.display='block' 
          error = 1
         }

         
         else if(error == 0){
            if(part==4){
              document.getElementById("school_popup3").style.display = 'none'
              document.getElementById("school_popup4").style.display = 'block'  
              document.getElementById("school_popup2").style.display = 'none'
            }
            else{
            document.getElementById("school_popup2").style.display = 'none'
            document.getElementById("school_popup4").style.display = 'none'
            document.getElementById("school_popup3").style.display = 'block'
            }
            
         }
         
         window.scroll(0,0)
         
     
         
    }   
    
    
    
    
     function validation_4(part){
        var motherName = document.forms['form']['motherName'].value
        var motherEmail = document.forms['form']['motherEmail'].value
        var motherJob = document.forms['form']['motherJob'].value
        var motherMobNum = document.forms['form']['motherMobNum'].value
        var motherCertf = document.forms['form']['motherCertf'].value
        var motherCertfP = document.forms['form']['motherCertfP'].value
        var motherCardNum = document.forms['form']['motherCardNum'].value
        var motherCardP = document.forms['form']['motherCardP'].value
        
       
        var error=0
         if(motherName==''){ 
          document.getElementById("motherName_error").innerHTML='you must complete this field' 
          document.getElementById("motherName_error").style.display='block' 
          error = 1
         }
         
         if(motherEmail==''){ 
          document.getElementById("motherEmail_error").innerHTML='you must complete this field' 
          document.getElementById("motherEmail_error").style.display='block' 
          error = 1
         }
         
         if(motherJob==''){ 
          document.getElementById("motherJob_error").innerHTML='you must complete this field' 
          document.getElementById("motherJob_error").style.display='block' 
          error = 1
         }
         if(motherMobNum==''){ 
          document.getElementById("motherMobNum_error").innerHTML='you must complete this field' 
          document.getElementById("motherMobNum_error").style.display='block' 
          error = 1
         }
         if(motherCertf==''){ 
          document.getElementById("motherCertf_error").innerHTML='you must complete this field' 
          document.getElementById("motherCertf_error").style.display='block' 
          error = 1
         }
         if(motherCertfP==''){ 
          document.getElementById("motherCertfP_error").innerHTML='you must complete this field' 
          document.getElementById("motherCertfP_error").style.display='block' 
          error = 1
         }
         if(motherCardNum==''){ 
          document.getElementById("motherCardNum_error").innerHTML='you must complete this field' 
          document.getElementById("motherCardNum_error").style.display='block' 
          error = 1
         }
         if(motherCardP==''){ 
          document.getElementById("motherCardP_error").innerHTML='you must complete this field' 
          document.getElementById("motherCardP_error").style.display='block' 
          error = 1
         }

         
         else if(error == 0){
            if(part==5){
              document.getElementById("school_popup4").style.display = 'none'
            document.getElementById("school_popup5").style.display = 'block'  
            document.getElementById("school_popup3").style.display = 'none'
            }
            else{
            document.getElementById("school_popup5").style.display = 'none'
            document.getElementById("school_popup3").style.display = 'none'
            document.getElementById("school_popup4").style.display = 'block'
            }
            
         }
         
         window.scroll(0,0)
         
     
         
    }
