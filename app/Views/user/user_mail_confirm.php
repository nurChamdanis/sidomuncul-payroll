<html>
    <head></head>
    <body>
        <table align="center" width="750" border="0" cellspacing="0" cellpadding="20px" style="border:1px solid #d8d8d8;border-radius:3px;overflow:hidden">
            <tr>
                <td align="center">

                    <table width="700" border="0" cellspacing="0" cellpadding="5px">
                        <tbody>
                            <tr>
                                <td align="center" style="font-family:helvetica neue,helvetica;font-size:18px;line-height:24px;color:#454545;">
                                    <a href="<?php echo base_url(); ?>"><img src="https://arkamayaerp.id/logo_app_sm.png" alt="Logo"></a>
                                </td>
                            </tr>
                            <tr>
                                <td><hr style=" border: 0; height: 1px; background: #333; background-image: linear-gradient(to right, #ccc, #333, #ccc);"></td>
                            </tr>
                            <!--tr>
                                <td align="center" style="font-family:helvetica neue,helvetica;font-size:16px;line-height:24px;color:#fff;background-color:#4BD396;">
                                    <p></p>
                                    <p>
                                        Terima kasih sudah mendaftar dan memilih <strong>Corporate Portal Management System (CPMS)</strong> sebagai 
                                        aplikasi yang akan membantu perusahaan anda. 
                                    </p>
                                    <p></p>
                                </td>
                            </tr-->
                            <tr>
                                <td width="600" align="center" style="font-family:helvetica neue,helvetica;font-size:16px;font-family:helvetica neue,helvetica;font-size:16px;padding:10px;background-color:#ffffff;border-collapse:collapse">
                                    <p>Hi, Anda telah diundang oleh <?php echo $this->session->userdata(S_EMPLOYEE_NAME)?> untuk menggunakan <?php echo APP_NAME?>. <br/><br/>
									Untuk mulai mengakses perusahaan <?php echo $this->session->userdata(S_COMPANY_NAME)?>, Silahkan klik </p>
                                    <a target="_blank" style="text-decoration:none;display:block" href="<?php echo base_url() . 'user/confirm/' . $user->email_confirmed_code; ?>">
                                        <table height="40" border="0" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                    <td height="38" style="background-color:#188AE2;padding:2px 15px 0px 15px;border-radius:3px">
                                                        <a target="_blank" style="font-family:helvetica;font-size:16px;color:#ffffff;text-decoration:none;display:block" href="<?php //echo $activation_link;  ?>">
                                                            Terima Undangan
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </a>
                                    <p>Jika link diatas tidak bisa diklik, salin link dibawah ini dan buka pada browser Anda.</p>
                                    <a target="_blank" onmouseover="this.style.color = 'CornflowerBlue'" onmouseout="this.style.color = '#333'" href="<?php echo base_url() . 'user/confirm/' . $user->email_confirmed_code; ?>"><?php echo base_url() . 'user/confirm/' . $user->email_confirmed_code; ?></a>
                                    <p></p>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td width="600" align="left" style="font-family:helvetica neue,helvetica;font-size:12px;font-family:helvetica neue,helvetica;font-size:12px;padding:10px;background-color:#ffffff;border-collapse:collapse">
                                    <strong><?php echo APP_NAME ?></strong>
                                    <p><a href="<?php echo base_url(); ?>" style="color:#777;text-decoration: none;"><strong><?php echo base_url(); ?></strong></a></p>
                                </td>
                            </tr>
                            
                        </tbody>
                    </table>

                </td>
            </tr>
        </table>
    </body>
</html>