<?php


class qaVoteTemplateOperators
{
    function qaVoteTemplateOperators()
    {
    }

    function operatorList()
    {
        return array( 'fetch_qavote_data',
                      'fetch_qavote_stats',
                      'fetch_by_qavote'
                      );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'fetch_qavote_data' => array( 'params' => array( 'type' => 'array',
                                              'required' => true,
                                              'default' => array() )),
                      'fetch_qavote_stats' => array( 'object_id' => array( 'type' => 'integer',
                                              'required' => true,
                                              'default' => 0 )),
                      'fetch_by_qavote' => array( 'params' => array( 'type' => 'array',
                                              'required' => false,
                                              'default' => array() ))
        );
                                              
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        switch ( $operatorName )
        {
            case 'fetch_qavote_data':
            {
                $ret = qaVoteDataObject::fetchByConds( $namedParameters['params'] );
            } break;
            case 'fetch_qavote_stats':
            {
                $ret = qaVoteObject::stats( $namedParameters['object_id'] );
            } break;
            case 'fetch_by_qavote':
            {
                $ret = qaVoteObject::fetchNodeByRating( $namedParameters['params'] );
            } break;
        }
        $operatorValue = $ret;
    }
}

?>