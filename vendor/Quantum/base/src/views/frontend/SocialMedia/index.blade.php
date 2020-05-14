<script>
function copyText(){
	var text = document.getElementById("copy_link");
	text.select();
	text.setSelectionRange(0, 99999); /*For mobile devices*/
	 document.execCommand("copy");
    alert("Copied the text: " + text.value);
}
</script>

<center>
<a href="https://m.me/1027062620708425?ref=motors">
<div style="border-radius: 3px; border: 0px solid #bbb; padding: 5px; margin: 10px; max-width: 500px; background-color: #A3DCE7; color: #fff">
<i class="fab fa-facebook-messenger"></i> Send Facebook Message
</div>
</a>

<a href="https://www.facebook.com/sharer/sharer.php?u=https://gaukmotors.co.uk" target="_blank">
<div style="border-radius: 3px; border: 0px solid #bbb; padding: 5px; margin: 10px; max-width: 500px; background-color: #2679A5; color: #fff">
<i class="fab fa-facebook-square"></i> Share on Facebook
</div>
</a>


<a href="#" onclick="copyText()">
<div style="border-radius: 3px; border: 1px solid #bbb; padding: 5px; margin: 10px; max-width: 500px">
<i class="far fa-copy"></i> Copy link
</div>
</a>

<!-- ttp://twitter.com/share?text=text goes here&url=http://url goes here&hashtags=hashtag1,hashtag2,hashtag3 -->
<a href="https://twitter.com/intent/tweet?url=https://gaukmotors.co.uk">
<div style="border-radius: 3px; border: 1px solid #bbb; padding: 5px; margin: 10px; max-width: 500px">
<i class="fab fa-twitter"></i> Tweet to your followers
</div>
</a>

<a href="/contact">
<div style="border-radius: 3px; border: 1px solid #bbb; padding: 5px; margin: 10px; max-width: 500px">
<i class="fas fa-envelope-open"></i> Send an email
</div>
</a>

<input type="text" value="http://gaukmotors.co.uk" id="copy_link" style="color: #fff; border:0; border-color: #fff">

</center>
