<?php
    include_once '_tool/helper.php';
    outputHeader();
?>

<h4>
    最新動態: <span data-bind="text: news">----</span>
</h4>
<hr/>

<div id="createEmailBlock">
    <div class="row">

        <div class="col-md-4">

            <div class="form-group">
                <label>Name</label>
                <input class="form-control" type="text" placeholder="enter full name" data-bind="value: name, valueUpdate: ['afterkeydown', 'input']">
            </div>
            <div class="form-group">
                <label>Age</label>
                <input class="form-control" type="text" placeholder="enter age" data-bind="value: age, valueUpdate: ['afterkeydown', 'input']">
            </div>
            <input type="button" data-bind="value: email, click: createEmail, enable: email()" />

        </div>
        <div class="col-md-4">

            <table class="table table-striped table-condensed table-bordered">
                <thead>
                    <tr>
                        <th style="width:15px;"><input class="checkbox" type="checkbox" id="chooseItemsAll" value="1" /></th>
                        <th> email </th>
                    </tr>
                </thead>
                <tbody data-bind="foreach: emailList">
                    <tr>
                        <td><input class="checkbox" type="checkbox" /></td>
                        <td><span data-bind="text: $data">----</span></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">
                            <div class="pull-right">
                                <button id="form-delete-submit" class="btn btn-danger">Delete</button>
                            </div>
                        </th>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>

</div>

<script type="text/javascript" charset="utf-8">
    "use strict";

    // 顯示要建立的帳號
    var CreateEmailModel = function()
    {
        var def = {
            name: 'johnny',
            age:  18,
        };
        var the = this;
        
        // 已建立的 email
        the.emailList = ko.observableArray([]);

        the.name = ko.observable( def.name );
        the.age  = ko.observable( def.age );

        the.email = ko.computed(function() {
            if ( the.name() && the.age() ) {
                return the.name() + "." + the.age() + "@gmail.com";
            }
            return '';
        }, the);


        the.createEmail = function() {
            if ( !the.email() ) {
                return;
            }
            the.emailList.push( the.email() );
            the.name('');
            the.age('');
        };

        the.filteredEmails = function() {
            
        };

    };
    var createEmailModel = new CreateEmailModel();
    ko.applyBindings( createEmailModel , document.getElementById("createEmailBlock") );



</script>








<?php outputFooter(); ?>