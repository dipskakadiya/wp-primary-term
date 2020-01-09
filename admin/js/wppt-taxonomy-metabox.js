/* global wp, _, WordPressPrimaryCategory */
( function ( $ ) {
    "use strict";

    var primaryTermInputTemplate, primaryTermElementTemplate, primaryTermRenderTemplate;
    var taxonomies = WordPressPrimaryCategory.taxonomies;

    /* Allow to check `Make Primary` button element exist or not for term
     *
     * @since 1.0
     * @return bool
     */
    function isPrimaryTermElement( termItem ) {
        return 1 === termItem.children( ".easy-make-primary-term" ).length;
    }

    /* Crete `Make Primary` button for term
     *
     * @since 1.0
     * @return void
     */
    function createPrimaryTermElement( term, taxonomy  ) {
        var termLabel, htmlPrimaryEle;

        termLabel = $( term ).closest( "label" );
        htmlPrimaryEle = primaryTermElementTemplate({
            taxonomy: taxonomy,
        });
        termLabel.after( htmlPrimaryEle );
    }

    /* Get primary term id which set in primary term input
     *
     * @since 1.0
     * @return int|null
     */
    function getPrimaryTerm( taxonomyName ) {
        var primaryTermInput;

        primaryTermInput = $( "#wppt-primary-" + taxonomyName );
        return primaryTermInput.val();
    }

    /* Allow to add status of primary term for all selected terms
     * Primary term element only visible if more then one term selected.
     * If single term selected and by default selected term is primary term.
     *
     * @since 1.0
     * @return void
     */
    function updatePrimaryTerm( taxonomy ) {
        var taxonomyName, selectedTerms, termsListItems, termItem, termLabel, htmlPrimaryRender;
        taxonomyName = taxonomy.name;
        selectedTerms = $( "#" + taxonomyName + 'checklist input[type="checkbox"]:checked' );
        termsListItems = $( "#" + taxonomyName + "checklist li" );

        $( "#" + taxonomyName + " .wppt-primary-term-button" ).remove();
        $( "#" + taxonomyName + " span.wppt-primary-term-render-label" ).remove();
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

    /* Allow to handle term checked/uncheck event.
     * Set First term as default primary term if primary term not set on this event
     *
     * @since 1.0
     * @return void
     */
    function termselectionChange( taxonomy ) {
        return function ( e ) {

            if ( false === $( this ).prop( "checked" ) && $( this ).val() === getPrimaryTerm( taxonomy.name ) ) {
                makeFirstTermPrimary( taxonomy );
            }
            defaultPrimaryTerm(taxonomy);
            updatePrimaryTerm(taxonomy);
        };
    }

    /* Handle listitem add event to add Primary term button for selected term
     *
     * @since 1.0
     * @return void
     */
    function newTermAdded( taxonomy ) {
        return function ( e ) {
            defaultPrimaryTerm( taxonomy );
            updatePrimaryTerm(taxonomy);
        };
    }

    /* Allow to set given termID as value of primary term input.
     * @since 1.0
     * @return void
     */
    function setPrimaryTerm( termId, taxonomy ) {
        var primaryTermInput;
        primaryTermInput = $( "#wppt-primary-" + taxonomy.name );
        primaryTermInput.val( termId ).trigger( "change" );
    }

    /* Handle `Make Primary` button click event amd set relative term as primary term.
     * @since 1.0
     * @return function
     */
    function makePrimaryTerm( taxonomy ) {
        return function ( e ) {
            var btnPrimaryTerm, termInput;
            btnPrimaryTerm = $(this);
            termInput = btnPrimaryTerm.siblings( "label" ).find( "input" );
            setPrimaryTerm( termInput.val(), taxonomy );
            updatePrimaryTerm(taxonomy);
        };
    }

    /* Set First term as primary term
     *
     * @since 1.0
     * @return void
     */
    function makeFirstTermPrimary( taxonomy ) {
        var firstTerm = $( "#" + taxonomy.name + 'checklist input[type="checkbox"]:checked:first' );
        setPrimaryTerm( firstTerm.val(), taxonomy );
    }

    /* Set First term as default primary term if primary term is not check
     *
     * @since 1.0
     * @return void
     */
    function defaultPrimaryTerm( taxonomy ) {
        if ( "" === getPrimaryTerm( taxonomy.name ) ) {
            makeFirstTermPrimary( taxonomy );
        }
    }

    /* init primary term input on taxonomy metabox.
     * Added event hook on list update and list item checked/unchecked
     * Add Event action to make term as primary term and update primary term input value.
     *
     * @since 1.0
     * @return void
     */
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