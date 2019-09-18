function updateCategorySlug()
{
    var itemTitle = $( "#category-create #name" ).val();
    slug = convertToSlug(itemTitle);
    $( "#category-create #slug" ).val(slug);

}

function updateCategoryUpdateSlug()
{
    var itemTitle = $( "#category-update #name" ).val();
    slug = convertToSlug(itemTitle);
    $( "#category-update #slug" ).val(slug);

}

function updateCategoryChildSlug()
{
    var itemTitle = $( "#categoryChild-create #child_name" ).val();
    slug = convertToSlug(itemTitle);
    $( "#categoryChild-create #child_slug" ).val(slug);

}

function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/ /g,'-')
        .replace(/[^\w-]+/g,'');
}

$('#category-create #name').keyup(function() {
    updateCategorySlug();
});

$('#category-update #name').keyup(function() {
    updateCategoryUpdateSlug();
});

$('#categoryChild-create #child_name').keyup(function() {
    updateCategoryChildSlug();
});