            <div class="spot-content_row">
                <div class="spot-item spot-password">
                    <h1 class="text-center color"><?php echo Yii::t('spot', "Enter spot's password") ?></h1>
                </div>
                <div class="spot-item text-center spot-password">
                    <form id="passForm" method="post">
                        <input maxlength="4" name="pass" type="password" disabled
                            <?php if (!empty($wrongPass)): ?>
                                value="<?php echo $wrongPass; ?>" class="error"
                            <?php endif; ?>
                        >
                        <input type="hidden" name="token" value="<?php echo Yii::app()->request->csrfToken ?>">
                    </form>
                </div>
                    <div class="spot-item spot-password">
                        <table>
                            <tr>
                                <td class="text-right"><a >1</a></td>
                                <td class="text-center"><a >2</a></td>
                                <td class="text-left"><a >3</a></td>
                            </tr>
                            <tr>
                                <td class="text-right"><a >4</a></td>
                                <td class="text-center"><a >5</a></td>
                                <td class="text-left"><a >6</a></td>
                            </tr>
                            <tr>
                                <td class="text-right"><a >7</a></td>
                                <td class="text-center"><a >8</a></td>
                                <td class="text-left"><a >9</a></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-center"><a >0</a></td>
                                <td><span class="backspace">&#xe019;</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
