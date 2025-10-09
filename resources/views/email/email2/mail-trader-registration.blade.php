
@extends('email.email2.mail-layout')
{{-- <html>
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=3Dedge">
      <meta name="viewport" content="width=3Ddevice-width, initial-scale=
         1.0">
      <title></title>
      <style type="text/css">*, ::after, ::before{ box-sizing: border-box;}
         body{ padding: 0px 0px; margin: 0px 0px;}
         body{ font-family: Arial, Helvetica, sans-serif;}
         .mail-header-bottom{
            background-image: url('{{ asset("mail-assets") }}'); 

            
         }
      </style>
   </head>
   <body>
      <span style="display:none"><img src="#" width="1" height="1" alt="" style="display:block;height:0px;width:0px;max-width:0px;max-height:0px;overflow:hidden;" border="0"></span>
      <table border="0" cellpadding="0" cellspacing="0" style="border-spacing: 0pt; background-color: #e9e9e9;" width="100%">
         <tbody>
            <tr>
               <td>
                  <table align="center" border="0" cellpadding="0" cellspacing=
                     "0" style="width: 100%; max-width: 600px; border-spacing: 0pt;">
                     <tbody>
                        <tr>
                           <td style="background-color: #CCE8FF; padding: 0px 0px;">
                              <table border="0" cellpadding="0" cellspacing="0" style="border-spacing: 0pt;" width="100%">
                                 <tbody>
                                    <tr>
                                       <!-- IMAGE VAR -->
                                       <td style="padding: 0px 0px;"><a href="#" style="display: inherit;">
                                          <img alt="" src="{{asset('mail-assets/mail-header.jpeg')}}" style="
                                          width: 100%; display: inherit;"></a></td>
                                    </tr>
                                    <tr class="mail-header-bottom">
                                       <td style="padding: 0px 30px;">
                                          <table border="0" cellpadding="0" cellspacing="0" style=
                                             "border-spacing: 0pt;" width="100%">
                                             <tbody>
                                                <tr>
                                                   <td><img alt="round" src="{{asset('mail-assets/round-top.png')}}" style="width: 100%; display: block;"></td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                        <tr>
                           <td style="background-color: #F39561; padding: 0px 30px 30px;">
                              <table border="0" cellpadding="0" cellspacing="0" style="border-spacing: 0pt;" width="100%">
                                 <tbody>
                                    <tr>
                                       <td style="background-color: #ffffff; border-left: 1px solid#AED3F0; border-right: 1px solid #AED3F0; padding: 0px 42px">
                                          <table border="0" cellpadding="0" cellspacing="0" style=
                                             "border-spacing: 0pt;" width="100%">
                                             <tbody>
                                                <tr>
                                                    <td style="font-family: Arial, Helvetica, sans-serif; font-size: 16px; line-height: 24px; color: #062652; padding-top: 20px;">
                                                      <p>Welcome to {{$companyName}}! Your account has been successfully created.</p>
                                                      <p>To complete your registration and activate your account, please click the button below:</p>
                                                      
                                                      <!-- Verification Button -->
                                                      <table border="0" cellpadding="0" cellspacing="0" style="margin: 30px 0; border-spacing: 0pt;" width="100%">
                                                         <tbody>
                                                            <tr>
                                                               <td align="center">
                                                                  <table border="0" cellpadding="0" cellspacing="0" style="border-spacing: 0pt; background-color: #fd7e14; border-radius: 8px;">
                                                                     <tbody>
                                                                        <tr>
                                                                           <td style="padding: 15px 30px;">
                                                                              <a href="{{$activation_link}}" rel="nofollow" style="color: #ffffff; text-decoration: none; font-family: Arial, Helvetica, sans-serif; font-size: 16px; font-weight: bold; display: inline-block;">
                                                                                 Click here for Account verification
                                                                              </a>
                                                                           </td>
                                                                        </tr>
                                                                     </tbody>
                                                                  </table>
                                                               </td>
                                                            </tr>
                                                         </tbody>
                                                      </table>
                                                      
                                                      <p style="margin-top: 20px; font-size: 14px; color: #666;">
                                                         If the button doesn't work, you can copy and paste this link into your browser:<br>
                                                         <a href="{{$activation_link}}" style="color: #fd7e14; word-break: break-all;">{{$activation_link}}</a>
                                                      </p>
                                                      
                                                      <p style="margin-top: 20px;">
                                                         This link will expire in 24 hours for security reasons.
                                                      </p>
                                                   </td> 
                                                </tr>
                                             </tbody>
                                          </table>	
                                       </td>
                                    </tr>
                                    <tr>
                                       <td><img alt="round" src="{{asset('mail-assets/round-bottom.png')}}" style="width: 100%; display: inherit;"></td>
                                    </tr>
                                    <tr>
                                       <td style="font-family: Arial, Helvetica, sans-serif; font-size: 22px; font-weight: bold; text-align: center; padding: 50px 0px 0px;"></td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                       

                        <tr>
                           <td><a href="mailto:support@coreprimemarkets.com" style="width: 100%; display: inherit;">
                            <img alt="support" src="{{asset('mail-assets/support-mail.jpeg')}}" style="display: block;"></a></td>
                        </tr>
                        <tr>
                           <td style="background-color: #fd7e14; padding: 50px 30px 0px;">
                              <table align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing: 0pt;" width="100%">
                                 <tbody>
                                    <tr>
                                       <td>
                                          <table align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing: 0pt;">
                                             <tbody>
                                                <tr>
                                                   <td style="padding: 0px 5px;"><a href="" style="display: inherit;" target="_blank">
													<img alt="facebook" src="{{asset('mail-assets/icon-facebook.png')}}" style="display: inherit;"></a></td>
                                                   <td style="padding: 0px 5px;"><a href="" style="display: inherit;" target="_blank">
													<img alt="instagram" src="{{asset('mail-assets/icon-instagram.png')}}" style="display: inherit;"></a></td=
                                                      >
                                                   
                                                   <td style="padding: 0px 5px;"><a href="" style="display: inherit;" target="_blank">
                                                      <img alt="linkedin" src="{{asset('mail-assets/icon-linkedin.png')}}" style="display: inherit;">
                                                   </a>
                                                </td>
                                                   <td style="padding: 0px 5px;">
                                                      <a href="" style="display: inherit;" target="_blank">
                                                         <img alt="twitter" src="{{asset('mail-assets/icon-twitter.png')}}"style="display: inherit;">
                                                      </a>
                                                   </td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td style="padding-top: 25px;">
                                          <table align="center" border="0" cellpadding="0" cellspac=
                                             "0" style="border-spacing: 0pt;">
                                             <tbody>
                                                <tr>
                                                   <td><a href="https://coreprimemarkets.com/" style="display: inherit; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 18px; color: #ffffff; 
                                                      text-decoration: none;" target="_blank">Privacy Policy</a></td>
                                                   <td style="font-family: Arial, Helvetica, sans-serif;  padding: 0px 8px; color: #ffffff;">|</td>
                                                   <td><a href="https://coreprimemarkets.com/" style="display: inherit; font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 18px; color: #ffffff; text-decoration: none;" target="_blank">
													  Terms &amp; Conditions</a>
                                                   </td>
                                                </tr>
                                             </tbody>
                                          </table>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td style="height: 20px; text-align: center; padding-top: 15px;"><img alt="separator" src="{{asset('mail-assets/email-footer-separator.png')}}"></td>
                                    </tr>
                                    <tr>
                                       <td style="height: 65px; font-family: Arial, Helvetica, sans-=
                                          serif; font-size: 12px; color: #ffffff; text-align: center;"> Core Prime Ltd © 2024
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </td>
            </tr>
         </tbody>
      </table>
   </body>
</html> --}}


@section('image')
<a href="#" style="display: inherit;">
   <img alt="" src="{{asset('mail-assets/welcome-mail-header.jpeg')}}" style=" width: 100%; display: inherit;">
</a>
@endsection

@section('class-img', asset('mail-assets/welcome-mail-header-bottom.jpeg'))

@section('content')
<p>Activate your Core Prime Ltd account by clicking the link below.
</p>
<p>
   <a href="{{$activation_link}}" rel="nofollow" style="color: #fd7e14;">Link to e-mail address verification</a>
</p>
@endsection