var VIPAAdvancedSearch = {
    searchQueryInput: $('#searchQueryInput'),
    searchFieldsArea: $('#advanced-search-fields-area'),
    searchFieldTemplate: $('#search-field-template').html(),
    init: function(){
        console.log('VIPA Advanced Search System Starts!');
        this.insertFirstFieldItem();
        this.addFieldItem();
    },
    getFirstFieldItem: function(){
        return this.searchFieldsArea.find('.search-field-items').eq(0);
    },
    getLastFieldItem: function(){
        return this.searchFieldsArea.find('.search-field-items').last();
    },
    searchQueryValue: function(){
        return this.searchQueryInput.val();
    },
    addFieldItem: function(){
        this.getLastFieldItem().find('.add-field-item').addClass('hidden');
        this.searchFieldsArea.append(this.searchFieldTemplate);
        this.normalizeFieldOptions();
    },
    removeFieldItem: function(item){
        var getItem = $(item).closest('.search-field-items');
        if(getItem.is(':first-child')){
            this.insertFirstFieldItem();
        }
        if(getItem.is(':last-child')){
            this.addFieldItem();
        }
        getItem.remove();
        this.updateInputQuery();
    },
    insertFirstFieldItem: function () {
        this.searchFieldsArea.prepend(this.searchFieldTemplate);
        this.getFirstFieldItem().find('.condition-form').addClass('hidden');
        this.getFirstFieldItem().find('.add-field-item').addClass('hidden');
    },
    updateInputQuery: function(){
        var searchQuery,firstItem,fieldItemValue,itemSearchField,itemCondition;
        searchQuery = '';
        firstItem = false;
        this.normalizeFieldOptions();
        $.each(this.searchFieldsArea.find('.search-field-items'), function( index, item){
            fieldItemValue = $(item).find('.field-item-value').val();
            itemSearchField = $(item).find('.item-search-field').val();
            itemCondition = $(item).find('.item-condition').val();
            if(fieldItemValue.length>0){
                if(!firstItem){
                    searchQuery = itemSearchField+':'+fieldItemValue;
                    firstItem = true;
                }else{
                    searchQuery = searchQuery+' '+itemCondition+' '+itemSearchField+':'+fieldItemValue;
                }
            }
        });
        $('#searchQueryInput').val(searchQuery);
    },
    redirectToSearch: function(){
        var searchQuery = $('#searchQueryInput').val();
        if(searchQuery.length>0){
            window.location = '/search?q=advanced:'+searchQuery;
        }
    },
    normalizeFieldOptions: function(){
        var itemSearchFieldValue,firstItemInputSection;
        firstItemInputSection = this.searchFieldsArea
            .find('.search-field-items')
            .eq(0)
            .find('.item-search-field')
            .val()
            .split('.')[0]
            ;
        $.each(this.searchFieldsArea.find('.search-field-items'), function( index, item){
            if(index == 0){
                return;
            }
            $.each($(item).find('.item-search-field option'), function(optionIndex, option){
                itemSearchFieldValue = $(option).val();
                if(itemSearchFieldValue.split('.')[0] == firstItemInputSection){
                    $(option).show();
                }else{
                    $(option).hide();
                }
            });
            if($(item).find('.item-search-field').val().split('.')[0] !== firstItemInputSection){
                $(item).find('.item-search-field').val($(item).find('.item-search-field option[style="display: block;"]').val());
            }
        });
    }
};
VIPAAdvancedSearch.init();


