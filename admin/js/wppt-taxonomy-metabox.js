/* global wp, _, WordPressPrimaryCategory */
( function ( $ ) {
    "use strict";

    var primaryTermInputTemplate, primaryTermElementTemplate, primaryTermRenderTemplate;
    var taxonomies = WordPressPrimaryCategory.taxonomies;

    function isPrimaryTermElement( termItem ) {
        return 1 === termItem.children( ".easy-make-primary-term" ).length;
    }

    function createPrimaryTermElement( term, taxonomy  ) {
        var termLabel, htmlPrimaryEle;

        termLabel = $( term ).closest( "label" );
        htmlPrimaryEle = primaryTermElementTemplate({
            taxonomy: taxonomy,
        });
        termLabel.after( htmlPrimaryEle );
    }

    function getPrimaryTerm( taxonomyName ) {
        var primaryTermInput;

        primaryTermInput = $( "#wppt-primary-" + taxonomyName );
        return primaryTermInput.val();
    }

    function updatePrimaryTerm( taxonomy ) {
        var taxonomyName, selectedTerms, termsListItems, termItem, termLabel, htmlPrimaryRender;
        taxonomyName = taxonomy.name;
        selectedTerms = $( "#" + taxonomyName + 'checklist input[type="checkbox"]:checked' );
        termsListItems = $( "#" + taxonomyName + "checklist li" );

        $( ".wppt-primary-term-button" ).remove();
        $( "span.wppt-primary-term-render-label" ).remove();
        termsListItems.removeClass( "wppt-primary-term" );

        // If there is only one term selected we don't needed primary term
        if ( selectedTerms.length <= 1 ) {
            return;
        }

        selectedTerms.each( function ( i, term ) {
            term = $( term );
            termItem = term.closest( "li" );
            //termItem.find("span.wppt-primary-term-render-label").remove();
            if ( term.val() === getPrimaryTerm( taxonomyName ) ) {
                termItem.addClass( "wppt-primary-term" );
                termLabel = term.closest( "label" );
                htmlPrimaryRender = primaryTermRenderTemplate({
                    taxonomy: taxonomy,
                });
                termLabel.after( htmlPrimaryRender );
            } else {
                //if primary term element already set or not
                if ( !isPrimaryTermElement( termItem ) ){
                    createPrimaryTermElement( term, taxonomy )
                }
            }
        } );

    }

    function termselectionChange( taxonomy ) {
        return function ( e ) {

            if ( false === $( this ).prop( "checked" ) && $( this ).val() === getPrimaryTerm( taxonomy.name ) ) {
                makeFirstTermPrimary( taxonomy );
            }
            defaultPrimaryTerm(taxonomy);
            updatePrimaryTerm(taxonomy);
        };
    }

    function newTermAdded( taxonomy ) {
        return function ( e ) {
            defaultPrimaryTerm( taxonomy );
            updatePrimaryTerm(taxonomy);
        };
    }

    function setPrimaryTerm( termId, taxonomy ) {
        var primaryTermInput;
        primaryTermInput = $( "#wppt-primary-" + taxonomy.name );
        primaryTermInput.val( termId ).trigger( "change" );
    }

    function makePrimaryTerm( taxonomy ) {
        return function ( e ) {
            var btnPrimaryTerm, termInput;
            btnPrimaryTerm = $(this);
            termInput = btnPrimaryTerm.siblings( "label" ).find( "input" );
            setPrimaryTerm( termInput.val(), taxonomy );
            updatePrimaryTerm(taxonomy);
        };
    }

    function makeFirstTermPrimary( taxonomy ) {
        var firstTerm = $( "#" + taxonomy.name + 'checklist input[type="checkbox"]:checked:first' );
        setPrimaryTerm( firstTerm.val(), taxonomy );
    }

    function defaultPrimaryTerm( taxonomy ) {
        if ( "" === getPrimaryTerm( taxonomy.name ) ) {
            makeFirstTermPrimary( taxonomy );
        }
    }

    function initWPPrimaryCategory ( taxonomy ) {
        var taxonomyMetabox, htmlInput;
        taxonomyMetabox = $(  "#" + taxonomy.name + "div");

        htmlInput = primaryTermInputTemplate({
            taxonomy: taxonomy,
        });

        taxonomyMetabox.append(htmlInput);
        updatePrimaryTerm( taxonomy );

        taxonomyMetabox.on( "click", ".wppt-primary-term-button", makePrimaryTerm( taxonomy ) );
        taxonomyMetabox.on( "click", 'input[type="checkbox"]', termselectionChange( taxonomy ) );
        taxonomyMetabox.on( "wpListAddEnd", "#" + taxonomy.name + "checklist", newTermAdded( taxonomy ) );
    }

    $( function () {

        // Initialize our templates
        primaryTermInputTemplate = wp.template( "wppt-primary-term-input" );
        primaryTermElementTemplate = wp.template( "wppt-primary-term-element" );
        primaryTermRenderTemplate = wp.template( "wppt-primary-term-render" );

        $( _.values( taxonomies ) ).each( function ( i, taxonomy ) {
            initWPPrimaryCategory( taxonomy );
        } );
    });
}( jQuery ) );