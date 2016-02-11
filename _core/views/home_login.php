<!DOCTYPE html">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>BDO Indonesia</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- End Google Analytics tracking code -->
    <link rel="stylesheet" href="<?=base_url()?>/css/style.css" type="text/css" />
	<link rel="stylesheet" href="<?=base_url()?>/css/custom.css" type="text/css" />
</head>
<body>
<form method="post" action="<?=base_url()?>home/login/" id="form1">
    <div class="header">
        <a class="repliconLogo" href="#" target="_blank" title="Replicon Inc - Timesheet Software"></a>
        <dl class="contactNumbers">
            <dt>Date</dt><dd><?=date('d/m/Y H:i:s')?></dd>
            <dt>Email Support</dt><dd>support@bdoindonesia.com</dd>
        </dl>
        
    </div>
    <div class="pageBody">
       
        <div class="bodyContent">
              
    
    
    <!-- Content. -->
    <div id="content">
        <div class="clearfix" id="main-area">
            <div id="company-textarea" class="normal">
                
                
                <div id="rich-text">
                    <div id="pnlInternalLogin" class="formBlock loginForm">
	
                        <h2 id="CustLoginLabel">Employee Login</h2>
                        <div class="loginArea">
                            <div id="loginError" class="loginError">
                                <p id="presistentErrorMessage">
                                    <?=$msg?>
                                </p>
                            </div>
                            <dl class="loginFields">
                                <dt>
                                    <label id="UserNameLabel" for="LoginNameTextBox">NIK</label>
                                </dt>
                                <dd>
                                    <input name="nik" type="text" maxlength="128" id="LoginNameTextBox" tabindex="2" />
                                </dd>
                                <dt>
                                    <label id="PwdLabel" for="PasswordTextBox">Password</label>
                                </dt>
                                <dd>
                                    <input name="pass" type="password" maxlength="128" id="PasswordTextBox" tabindex="3" />
                                </dd>
                            </dl>
                            <div class="forgotPassword">
                                <a href="Password_Reset.aspx" id="PwdForgotlnk" tabindex="4" onclick="modifyPasswordResetLink();">
                                    <span id="PwdForgotLabel">Forgot Password?</span>
                                </a>
                            </div>
                            <div class="buttonRow">
                                <input type="submit" name="Login" value="Login" id="LoginButton" tabindex="4" class="important loginButton" type="button" />
                                
                            </div>
                           
                           
                            
                        </div>
                    
</div>
                    
                    <div class="promotion"><a class="repliconMobile" href="#" alt="Replicon Mobile"></a></div>
                        <div class="sweeper"></div>
                    </div>
            </div>
        
        <div style='visibility: hidden' id='preloadimages'>
            <span id="PngImage3" title="Loading" style="display:inline-block;height:15px;width:128px;background-image:url(/WebResource.axd?d=7DwoZDZ80tMrSqenkcj5hjJ0nJHJLvRyR0L8h0z2DOXH08eJeBbV4OyUOdsr85jSf8FHRfbWLHY1ClGeR6OMIusLNDYYy_rFXqUY54tl-0AvCTV318-45NKaDZRn_7LN6rCN7qgLoE_TAVIyj6WCFvBhXJSjmkNdIFP5d0HKzEVgQn6p0&amp;t=635209146920000000);background-repeat:no-repeat;background-position:0px 0px;border-width:0px;"></span>
    </div>
    </div>
    </div>

        </div>
        
        </div>
    </div>
    </form> 
</body>
</html>