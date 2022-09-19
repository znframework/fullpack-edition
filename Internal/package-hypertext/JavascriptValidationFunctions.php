<?php if( $ZNValidationBetween ?? NULL ): ?>
<script>
function <?php echo $ZNValidationBetween ?>(element, min, max, message) 
{
    var value = Number(element.value);

    if( value < min || value > max || isNaN(value) ) 
    {
        element.setCustomValidity(message);
    }
    else
    {
        element.setCustomValidity('');
    }
}
</script>
<?php endif; ?>

<?php if( $ZNValidationCaptcha ?? NULL ): ?>
<script>
function <?php echo $ZNValidationCaptcha ?>(element, message) 
{
    var value = element.value;

    if( value !== '<?php echo ZN\Singleton::class('ZN\Captcha\Render')->getCode(); ?>' ) 
    {
        element.setCustomValidity(message);
    }
    else
    {
        element.setCustomValidity('');
    }
}
</script>
<?php endif; ?>

<?php if( $ZNValidationAnswer ?? NULL ): ?>
<script>
function <?php echo $ZNValidationAnswer ?>(element, message) 
{
    var value = element.value;

    if( value !== '<?php echo $_SESSION[md5('answerToQuestion')] ?>' ) 
    {
        element.setCustomValidity(message);
    }
    else
    {
        element.setCustomValidity('');
    }
}
</script>
<?php endif; ?>

<?php if( $ZNValidationMatch ?? NULL ): ?>
<script>
function <?php echo $ZNValidationMatch ?>(element, matchElementName, message) 
{
    var value = element.value;

    var matchElementValue = document.getElementsByName(element.form.name)[0].elements[matchElementName].value;

    if( value != matchElementValue ) 
    {
        element.setCustomValidity(message);
    }
    else
    {
        element.setCustomValidity('');
    }
}
</script>
<?php endif; ?>

<?php if( $ZNValidationPhone ?? NULL ): ?>
<script>
function <?php echo $ZNValidationPhone ?>(element, pattern, message) 
{
    var value = element.value;

    if( pattern )
    {
        phoneData = pattern.replace(/([^\*])/g, 'key:$1');
        phoneData = phoneData.replace(/\*/g, '[0-9]');
        phoneData = phoneData.replace(/key\:/g, '\\');

        phoneData = new RegExp('^' + phoneData + '$');
    }
    else
    {
        phoneData = /\+*[0-9]{10,14}$/;
    }

    if( ! value.match(phoneData) ) 
    {
        element.setCustomValidity(message);
    }
    else
    {
        element.setCustomValidity('');
    }
}
</script>
<?php endif; ?>

<?php if( $ZNValidationIdentity ?? NULL ): ?>
<script>
function <?php echo $ZNValidationIdentity ?>(element, message) 
{
    var value = element.value;

    if( value.length !== 11 )
    {
        return element.setCustomValidity(message);
    }
    
    v0 = Number(value[0]); v1 = Number(value[1]); v2 = Number(value[2]); v3 = Number(value[3]);
    v4 = Number(value[4]); v5 = Number(value[5]); v6 = Number(value[6]); v7 = Number(value[7]);
    v8 = Number(value[8]); v9 = Number(value[9]); v10 = Number(value[10]);

    firstNumbers  = v0 + v2 + v4 + v6  + v8;
    secondNumbers = v1 + v3 + v5 + v7;

    numone   = firstNumbers  * 7;
    numtwo   = secondNumbers * 9;
    numthree = firstNumbers  * 8;

    totalOneAndTwo = numone + numtwo;

    firstLastChar  = String(totalOneAndTwo).substr(-1, 1);
    secondLastChar = String(numthree).substr(-1, 1);
    
    if( v0 == 0 )
    {
        element.setCustomValidity(message);
    }
    else if( v9 != firstLastChar )
    {
        element.setCustomValidity(message);
    }
    else if( v10 != secondLastChar )
    {
        element.setCustomValidity(message);
    }
    else
    {
        element.setCustomValidity('');
    }
}
</script>
<?php endif; ?>