<?php
//
// Definition of eZContentDataInstace class
//
// Created on: <22-Apr-2002 09:31:57 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZContentObjectAttribute ezcontentobjectattribute.php
  \ingroup eZKernel
  \brief Encapsulates the data for an object attribute

  \sa eZContentObject eZContentClass eZContentClassAttribute
*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentclassattribute.php" );

class eZContentObjectAttribute extends eZPersistentObject
{
    /*!
    */
    function eZContentObjectAttribute( $row )
    {
        $this->Content = null;
        $this->ValidationError = null;
        $this->ValidationLog = null;
        $this->ContentClassAttributeIdentifier = null;
        $this->ContentClassAttributeID = null;
        $this->InputParameters = false;
        $this->HasValidationError = true;
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "contentobject_id" => array( 'name' => "ContentObjectID",
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         "version" => array( 'name' => "Version",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "language_code" => array( 'name' => "LanguageCode",
                                                                   'datatype' => 'string',
                                                                   'default' => '',
                                                                   'required' => true ),
                                         "contentclassattribute_id" => array( 'name' => "ContentClassAttributeID",
                                                                              'datatype' => 'integer',
                                                                              'default' => 0,
                                                                              'required' => true ),
                                         "data_text" => array( 'name' => "DataText",
                                                               'datatype' => 'text',
                                                               'default' => '',
                                                               'required' => true ),
                                         "data_int" => array( 'name' => "DataInt",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         "data_float" => array( 'name' => "DataFloat",
                                                                'datatype' => 'float',
                                                                'default' => 0,
                                                                'required' => true ) ),
                      "keys" => array( "id", "contentobject_id", "version", "language_code" ),
                      "function_attributes" => array( "contentclass_attribute" => "contentClassAttribute",
                                                      "contentclass_attribute_identifier" => "contentClassAttributeIdentifier",
                                                      "content" => "content",
                                                      "class_content" => "classContent",
                                                      "object" => "object",
                                                      'view_template' => 'viewTemplateName',
                                                      'edit_template' => 'editTemplateName',
                                                      "has_validation_error" => "hasValidationError",
                                                      "validation_error" => "validationError",
                                                      "validation_log" => "validationLog",
                                                      "language" => "language",
                                                      "is_a" => "isA"
                                                      ),
                      "increment_key" => "id",
                      "class_name" => "eZContentObjectAttribute",
                      "sort" => array( "id" => "asc" ),
                      "name" => "ezcontentobject_attribute" );
    }

    function &fetch( $id, $version, $asObject = true, $field_filters = null )
    {
        return eZPersistentObject::fetchObject( eZContentObjectAttribute::definition(),
                                                $field_filters,
                                                array( "id" => $id,
                                                       "version" => $version ),
                                                $asObject );
    }

    function &fetchListByClassID( $id, $version = false, $limit = null, $asObject = true, $asCount = false )
    {
        $conditions = array();
        if ( is_array( $id ) )
            $conditions['contentclassattribute_id'] = array( $id );
        else
            $conditions['contentclassattribute_id'] = $id;
        if ( $version !== false )
            $conditions["version"] = $version;
        $fieldFilters = null;
        $customFields = null;
        if ( $asCount )
        {
            $limit = null;
            $asObject = false;
            $fieldFilters = array();
            $customFields = array( array( 'operation' => 'count( id )',
                                          'name' => 'count' ) );
        }
        $objectList =& eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                                            $fieldFilters, $conditions,
                                                            null, $limit, $asObject,
                                                            null, $customFields );
        if ( $asCount )
            return $objectList[0]['count'];
        else
            return $objectList;
    }

    function &fetchSameClassAttributeIDList( $contentClassAttributeID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                                    null,
                                                    array( "contentclassattribute_id" => $contentClassAttributeID ),
                                                    null,
                                                    null,
                                                    $asObject);
    }

    function &create( $contentclassAttributeID, $contentobjectID, $version = 1 )
    {
        include_once( 'lib/ezlocale/classes/ezlocale.php' );
        $row = array(
            "id" => null,
            "contentobject_id" => $contentobjectID,
            "version" => $version,
            "language_code" => eZContentObject::defaultLanguage(),
            "contentclassattribute_id" => $contentclassAttributeID,
            'data_text' => '',
            'data_int' => 0,
            'data_float' => 0.0 );
        return new eZContentObjectAttribute( $row );
    }

    /*!

    */
    function store()
    {
        $classAttr =& $this->contentClassAttribute();
        $dataType =& $classAttr->dataType();

        // store the content data for this attribute
        $dataType->storeObjectAttribute( $this );

        return eZPersistentObject::store();
    }

    /*!
     Store one row into content attribute table
    */
    function storeNewRow()
    {
        return eZPersistentObject::store();
    }

    function &attribute( $attr )
    {
        if ( $attr == "contentclass_attribute" )
            return $this->contentClassAttribute();
        if ( $attr == "contentclass_attribute_identifier" )
            return $this->contentClassAttributeIdentifier();
        else if ( $attr == "content" )
            return $this->content( );
        else if ( $attr == "class_content" )
            return $this->classContent( );
        else if ( $attr == "object" )
            return $this->object( );
        else if ( $attr == "xml" )
            return $this->xml( );
        else if ( $attr == "xml_editor" )
            return $this->xmlEditor( );
        else if ( $attr == "has_validation_error" )
            return $this->hasValidationError( );
        else if ( $attr == "validation_error" )
            return $this->validationError( );
        else if ( $attr == "validation_log" )
            return $this->validationLog( );
        else if  ( $attr == "language" )
            return $this->language( );
        else if  ( $attr == "is_a" )
            return $this->isA( );
        else if ( $attr == 'view_template' )
            return $this->viewTemplateName();
        else if ( $attr == 'edit_template' )
            return $this->editTemplateName();
        else
            return eZPersistentObject::attribute( $attr );
    }

    function &language( $languageCode = false, $asObject=true )
    {
        $languageCode = eZContentObject::defaultLanguage();
        return eZPersistentObject::fetchObject( eZContentObjectAttribute::definition(),
                                                $field_filters,
                                                array( "contentclassattribute_id" => $this->ContentClassAttributeID ,
                                                       "version" => $this->Version ,
                                                       "language_code" => $languageCode
                                                       ),
                                                $asObject );
    }

    /*!
    */
    function &object()
    {
        return eZContentObject::fetch( $this->ContentObjectID );
    }

    /*!
      Returns the attribute  for the current data attribute instance
    */
    function &contentClassAttribute()
    {
        $classAttribute =& eZContentClassAttribute::fetch( $this->ContentClassAttributeID );
        return $classAttribute;
    }

    /*!
    */
    function &contentClassAttributeName()
    {
        $classAttribute =& eZContentClassAttribute::fetch( $this->ContentClassAttributeID );
        return $classAttribute->attribute( "name" );
    }

    /*!
     Sets the cached content class attribute identifier
    */
    function setContentClassAttributeIdentifier( $identifier )
    {
        $this->ContentClassAttributeIdentifier = $identifier;
    }

    /*!
     \return the idenfifier for the content class attribute
    */
    function &contentClassAttributeIdentifier()
    {
        if ( $this->ContentClassAttributeIdentifier === null )
        {
            $classAttribute =& eZContentClassAttribute::fetch( $this->ContentClassAttributeID );
            $this->ContentClassAttributeIdentifier = $classAttribute->attribute( 'identifier' );
//             eZDebug::writeDebug( "Identifier not cached '" . $this->ContentClassAttributeIdentifier . "', fetching from db", "eZContentClassAttribute::contentClassAttributeIdentifier()" );
        }
        return $this->ContentClassAttributeIdentifier;
    }

    /*!
      Validates the data contents, returns true on success false if the data
      does not validate.
    */
    function validateInput( &$http, $base,
                            &$inputParameters, $validationParameters = array() )
    {
        $classAttribute =& $this->contentClassAttribute();
        $definition =& $classAttribute->dataType();
        $this->setInputParameters( $inputParameters );
        $this->setValidationParameters( $validationParameters );
        $this->IsValid = $definition->validateObjectAttributeHTTPInput( $http, $base, $this );
        $this->unsetValidationParameters();
        $this->unsetInputParameters();
        return $this->IsValid;
    }

    /*!
     Sets the current input parameters to \a $parameters.
     The input parameters are set by validateInput() and made avaiable to
     datatypes trough the function inputParameters().
     \note The input parameters will only be available for the duration of validateInput().
     \sa inputParameters
    */
    function setInputParameters( &$parameters )
    {
        $this->InputParameters =& $parameters;
    }

    /*!
     Unsets the input parameters previously set by setInputParameters().
     \sa inputParameters
    */
    function unsetInputParameters()
    {
        unset( $this->InputParameters );
        $this->InputParameters = false;
    }

    /*!
     \return the current input parameters or \c false if no parameters has been set.
     \sa setInputParameters, unsetInputParameters
    */
    function &inputParameters()
    {
        return $this->InputParameters;
    }

    /*!
     Sets the current validation parameters to \a $parameters.
     The validation parameters are set by validateInput() and made avaiable to
     datatypes trough the function validationParameters().
     \note The validation parameters will only be available for the duration of validateInput().
     \sa validationParameters
    */
    function setValidationParameters( &$parameters )
    {
        $this->ValidationParameters =& $parameters;
    }

    /*!
     Unsets the validation parameters previously set by setValidationParameters().
     \sa validationParameters
    */
    function unsetValidationParameters()
    {
        unset( $this->ValidationParameters );
        $this->ValidationParameters = false;
    }

    /*!
     \return the current validation parameters or \c false if no parameters has been set.
     \sa setValidationParameters, unsetValidationParameters
    */
    function &validationParameters()
    {
        return $this->ValidationParameters;
    }

    /*!
      Tries to fixup the input text to be acceptable.
     */
    function fixupInput( &$http, $base )
    {
        $classAttribute =& $this->contentClassAttribute();
        $definition =& $classAttribute->dataType();
        $definition->fixupObjectAttributeHTTPInput( $http, $base, $this );
    }

    /*!
      Fetches the data from http post vars and sets them correctly.
    */
    function fetchInput( &$http, $base )
    {
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        return $dataType->fetchObjectAttributeHTTPInput( $http, $base, $this );
    }

    /*!
     Executes the custom HTTP action
    */
    function customHTTPAction( &$http, $action, $parameters = array() )
    {
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        $dataType->customObjectAttributeHTTPAction( $http, $action, $this, $parameters );
    }

    /*!
     Sends custom actions to datatype for custom handling.
    */
    function handleCustomHTTPActions( &$http, $attributeDataBaseName,
                                      $customActionAttributeArray, $customActionParameters )
    {
        $dataType =& $this->dataType();
        $dataType->handleCustomObjectHTTPActions( $http, $attributeDataBaseName,
                                                  $customActionAttributeArray, $customActionParameters );
    }

    function onPublish( &$object, &$nodes  )
    {
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        $dataType->onPublish( $this, $object, $nodes );
    }

    /*!
     Initialized the attribute by using the datatype.
    */
    function initialize( $currentVersion = null, $originalContentObjectAttribute = null )
    {
        if ( $originalContentObjectAttribute === null )
            $originalContentObjectAttribute = $this;
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        $dataType->initializeObjectAttribute( $this, $currentVersion, $originalContentObjectAttribute );
    }

    /*!
     Remove the attribute by using the datatype.
    */
    function &remove( $id, $currentVersion = null )
    {
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        if ( !$dataType )
            return false;
        $dataType->deleteStoredObjectAttribute( $this, $currentVersion );
        if( $currentVersion == null )
        {
            eZPersistentObject::removeObject( eZContentObjectAttribute::definition(),
                                              array( "id" => $id ) );
        }
        else
        {
            eZPersistentObject::removeObject( eZContentObjectAttribute::definition(),
                                              array( "id" => $id,
                                                     "version" => $currentVersion ) );
        }
    }

    /*!
     Clones the attribute with new version \a $newVersionNumber and old version \a $currentVersionNumber.
     \note The cloned attribute is not stored.
    */
    function &clone( $newVersionNumber, $currentVersionNumber, $contentObjectID = false )
    {
        $tmp = $this;
        $tmp->setAttribute( "version", $newVersionNumber );
        if ( $contentObjectID !== false )
        {
            if ( $contentObjectID != $tmp->attribute( 'contentobject_id' ) )
                $tmp->setAttribute( 'id', null );
            $tmp->setAttribute( 'contentobject_id', $contentObjectID );
        }
        $tmp->sync();
        $tmp->initialize( $currentVersionNumber, $this );
        return $tmp;
    }

    /*!
     Returns the data type class for the current attribute.
    */
    function &dataType()
    {
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();

        return $dataType;
    }

    /*!
      Fetches the title of the data instance which is to form the title of the object.
    */
    function title()
    {
        $classAttribute =& $this->contentClassAttribute();
        $definition =& $classAttribute->dataType();
        return $definition->title( $this );
    }

    /*!
     \return the content for the contentclass attribute which defines this contentobject attribute.
    */
    function classContent()
    {
        $attribute =& $this->contentClassAttribute();
        return $attribute->content();
    }

    /*!
     Returns the content for this attribute.
    */
    function content()
    {
        if ( $this->Content === null )
        {
            $attribute =& $this->contentClassAttribute();
            $dataType =& $attribute->dataType();
            $this->Content =& $dataType->objectAttributeContent( $this );
        }
        return $this->Content;
    }

    /*!
     Returns the metadata. This is the pure content of the attribute used for
     indexing data for search engines.
     */
    function metaData()
    {
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        if ( $dataType )
            return $dataType->metaData( $this );
        else
            return false;
    }


    /*!
     Sets the content for the current attribute
    */
    function setContent( $content )
    {
        $this->Content =& $content;
    }

    /*!
     Returns the content action(s) for this attribute
    */
    function &contentActionList()
    {
        $classAttribute =& $this->contentClassAttribute();
        $dataType =& $classAttribute->dataType();
        return $dataType->contentActionList( $classAttribute );
    }

    function setValidationError()
    {
        $numargs = func_num_args();
        if ( $numargs < 1 )
        {
            trigger_error( 'Function must take at least one parameter', WARNING );
            return;
        }
        $argList =& func_get_args();
        $text = eZContentObjectAttribute::generateValidationErrorText( $numargs, $argList );
        $this->ValidationError = $text;
        $this->HasValidationError = true;
    }

    function setHasValidationError( $hasError = true )
    {
        $this->HasValidationError = $hasError;
    }

    function hasValidationError()
    {
        return $this->HasValidationError;
    }

    function generateValidationErrorText( $numargs, $argList )
    {
        $text = $argList[0];
        for ( $i = 1; $i < $numargs; ++$i )
        {
            $arg = $argList[$i];
            $text =& str_replace( "%$i", $arg, $text );
        }
        return $text;
    }

    function setValidationLog( $text )
    {
        if ( is_string( $text ) )
        {
            $logMessage = array();
            $logMessage[] = $text;
            $this->ValidationLog = $logMessage;
        }
        elseif ( is_array( $text ) )
        {
            $this->ValidationLog = $text;
        }
        else
        {
            $this->ValidationLog = null;
        }
    }

    function validationError()
    {
        return $this->ValidationError;
    }

    function validationLog()
    {
        return $this->ValidationLog;
    }

    /*!
    */
    function &serialize()
    {
        $attribute =& $this->contentClassAttribute();
        $dataType =& $attribute->dataType();
        return $dataType->serializeContentObjectAttribute( $this );
    }

    /*!
    */
    function &isA()
    {
        $attribute =& $this->contentClassAttribute();
        $dataType =& $attribute->dataType();
        return $dataType->isA();
    }

    /*!
     \return the template name to use for viewing the attribute or
             if the attribute is an information collector the information
             template name is returned.
     \note The returned template name does not include the .tpl extension.
     \sa editTemplate, informationTemplate
    */
    function &viewTemplateName()
    {
        $classAttribute =& $this->contentClassAttribute();
        if ( $classAttribute->attribute( 'is_information_collector' ) )
            return $this->informationTemplate();
        else
            return $this->viewTemplate();
    }

    /*!
     \return the template name to use for editing the attribute.
    */
    function &editTemplateName()
    {
        return $this->editTemplate();
    }

    /*!
     \return the template name to use for viewing the attribute.
     \note The returned template name does not include the .tpl extension.
     \sa editTemplate, informationTemplate
    */
    function &viewTemplate()
    {
        $dataType =& $this->dataType();
        return $dataType->viewTemplate( $this );
    }

    /*!
     \return the template name to use for editing the attribute.
     \note The returned template name does not include the .tpl extension.
     \sa viewTemplate, informationTemplate
    */
    function &editTemplate()
    {
        $dataType =& $this->dataType();
        return $dataType->editTemplate( $this );
    }

    /*!
     \return the template name to use for information collection for the attribute.
     \note The returned template name does not include the .tpl extension.
     \sa viewTemplate, editTemplate
    */
    function &informationTemplate()
    {
        $dataType =& $this->dataType();
        return $dataType->informationTemplate( $this );
    }

    /// Contains the content for this attribute
    var $Content;

    /// Stores the is valid
    var $IsValid;

    var $ContentClassAttributeID;

    /// Contains the last validation error
    var $ValidationError;

    /// Contains the last validation error
    var $ValidationLog;

    ///
    var $ContentClassAttributeIdentifier;
}

?>
