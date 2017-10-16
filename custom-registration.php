<div class="acf-form">
    <div class="acf-fields acf-form-fields -top">
        <div id="tabs" class="acf-tab-wrap -top">

            <div id="tabs-1" style="display: block">
                <div class="acf-field acf-field-text">
                    <div class="acf-input">
                        <div class="acf-input-wrap">
                            <input id="lname" type="text" name="lname"
                                   placeholder="' . $local['lname'] . '"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="acf-field acf-field-text">
                    <div class="acf-input">
                        <div class="acf-input-wrap">
                            <input id="name" type="text" name="name"
                                   placeholder="' . $local['name'] . '"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="acf-field acf-field-text">
                    <div class="acf-input">
                        <div class="acf-input-wrap">
                            <input id="fname" type="text" name="fname"
                                   placeholder="' . $local['fname'] . '"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="acf-field acf-field-text">
                    <div class="acf-input">
                        <div class="acf-input-wrap">
                            <input id="email" type="text" name="email"
                                   placeholder="' . $local['email'] . '"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="acf-field acf-field-text number-phone">
                    <div class="acf-input">
                        <div class="acf-input-wrap">
                            <input id="phonenumber" type="text" name="email"
                                   placeholder="' . $local['phonenumber'] . '"
                                   value="" maxlength="9"/>
                        </div>
                    </div>
                </div>
                <div class="acf-field acf-field-select">
                    <div class="acf-input-wrap">
                        <select class="" name="city" id="city">
                            <option value="">' . $local['city'] . '</option>
                        </select>
                    </div>
                </div>
                <div class="acf-form-submit">
                    <input class="acf-button button button-primary button-large" type="button" name="buttonSubmit"
                           id="buttonSubmitPart1"
                           value="Далее"/>
                </div>
            </div>

            <div id="tabs-2" style="display: none">
                <div class="acf-field acf-field-select">
                    <div class="acf-input">
                        <select class="" name="payType" id="payType">
                            <option value="">Периодичность выплат</option>
                            <option value="Ежедневно">Ежедневно</option>
                            <option value="Еженедельно">Еженедельно</option>
                        </select>
                    </div>
                </div>
                <div class="acf-field acf-field-text">
                    <div class="acf-input">
                        <div class="acf-input-wrap">
                            <label for="bankCard">' . $local['bankCardLabel'] . '<strong
                                        style="color: red">*</strong></label>
                            <input id="bankCard" type="text" name="bankCard" maxlength="16"
                                   placeholder="' . $local['bankCard'] . '"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="acf-field acf-field-select">
                    <div class="acf-input">
                        <select id="carMark" class="acf-input" name="carMark">
                            <option value="">' . $local['carMark'] . '</option>
                        </select>
                    </div>
                </div>
                <div class="acf-field acf-field-select">
                    <div class="acf-input">
                        <select id="carModel" class="acf-input" name="carModel">
                            <option value="">Сначала выберете марку</option>
                        </select>
                    </div>
                </div>
                <div class="acf-field acf-field-select">
                    <div class="acf-input">
                        <select class="acf-input" name="carColor" id="carColor">
                            <option value="">Цвет</option>
                        </select>
                    </div>
                </div>
                <div class="acf-field acf-field-select">
                    <div class="acf-input">
                        <select class="acf-input" name="carYear" id="carYear">
                            <option value="">Год выпуска</option>
                            <option value="1990">1990</option>
                            <option value="1991">1991</option>
                            <option value="1992">1992</option>
                            <option value="1993">1993</option>
                            <option value="1994">1994</option>
                            <option value="1995">1995</option>
                            <option value="1996">1996</option>
                            <option value="1997">1997</option>
                            <option value="1998">1998</option>
                            <option value="1999">1999</option>
                            <option value="2000">2000</option>
                            <option value="2001">2001</option>
                            <option value="2002">2002</option>
                            <option value="2003">2003</option>
                            <option value="2004">2004</option>
                            <option value="2005">2005</option>
                            <option value="2006">2006</option>
                            <option value="2007">2007</option>
                            <option value="2008">2008</option>
                            <option value="2009">2009</option>
                            <option value="2010">2010</option>
                            <option value="2011">2011</option>
                            <option value="2012">2012</option>
                            <option value="2013">2013</option>
                            <option value="2014">2014</option>
                            <option value="2015">2015</option>
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                        </select>
                    </div>
                </div>
                <div class="acf-field acf-field-text">
                    <div class="acf-input">
                        <div class="acf-input-wrap">
                            <input id="carNumber" type="text" name="carNumber"
                                   placeholder="' . $local['carNumber'] . '"
                                   value=""/>
                        </div>
                    </div>
                </div>
                <div class="acf-form-submit">
                    <input class="acf-button button button-primary button-large" type="button" name="buttonSubmit"
                           id="buttonSubmitPart2"
                           value="Далее"/>
                </div>
            </div>

            <div id="tabs-3" style="display: none">
                <div>
                    <p>Загрузите фото (чёткое, необрезанное и незасвеченное, не более 5 МБ)</p>
                </div>
                <div>
                    <label class="acf-input-prepend" for="fileFrontPage">' . $local['fileFrontPage'] . '</label>
                    <input type="file" id="fileFrontPage" name="fileFrontPage" accept="image/*">
                </div>
                <div>
                    <label class="acf-input-prepend" for="fileKategory">' . $local['fileKategory'] . '</label>
                    <input type="file" id="fileKategory" name="fileKategory" accept="image/*">
                </div>
                <div>
                    <label class="acf-input-prepend" for="fileTechMark">' . $local['fileTechMark'] . '</label>
                    <input type="file" id="fileTechMark" name="fileTechMark" accept="image/*">
                </div>
                <div>
                    <label class="acf-input-prepend" for="fileTechNumber">' . $local['fileTechNumber'] . '</label>
                    <input type="file" id="fileTechNumber" name="fileTechNumber" accept="image/*">
                </div>
                <div>
                    <label class="acf-input-prepend" for="filePolis">' . $local['filePolis'] . '</label>
                    <input type="file" id="filePolis" name="filePolis" accept="image/*">
                </div>
                <div>
                    <input style="width: 10% !important;" type="checkbox" id="personalData" name="personalData"
                           value=""/>
                    <label for="personalData" style="font-size: 13px;">' . $local['personalData'] . '</label>
                </div>
                <div class="acf-form-submit">
                    <input class="acf-button button button-primary button-large" type="button" name="buttonSubmit"
                           id="buttonSubmitPart3"
                           value="Регистрация"/>
                </div>
            </div>

        </div>
        <div>
            <p>Остались вопросы?<br> Подбронее по ссылке <br><a href="http://uberlin.com.ua/welcometouber/">uberlin.com.ua/welcometouber</a>
                <br>или по телефону 7373</p>
        </div>

        <!-- The Modal -->
        <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
                <span class="close">&times;</span>
                <div id="modal-text">
                </div>
            </div>

        </div>
    </div>
</div>