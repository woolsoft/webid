<?php
/*
@homepage <https://github.com/semsol/arc2>
@license W3C Software License and GPL

class:    ARC2 SG API JSON Parser
author:   Benjamin Nowack
version:  2010-11-16
*/

ARC2::inc('JSONParser');

class ARC2_SGAJSONParser extends ARC2_JSONParser {

  function __construct($a, &$caller) {
    parent::__construct($a, $caller);
  }
  
  function __init() {/* reader */
    parent::__init();
    $this->rdf = 'http://www.w3.org/1999/02/22-rdf-syntax-ns#';
    $this->nsp = array($this->rdf => 'rdf');
  }
  
  /*  */

  function done() {
    $this->extractRDF();
  }

  function extractRDF($formats = '') {
    $s = $this->getContext();
    $os = $this->getURLs($this->struct);
    foreach ($os as $o) {
      if ($o != $s) $this->addT($s, 'http://www.w3.org/2000/01/rdf-schema#seeAlso', $o, 'uri', 'uri');
    }
  }
  
  function getContext() {
    if (!isset($this->struct['canonical_mapping'])) return '';
    foreach ($this->struct['canonical_mapping'] as $k => $v) return $v;
  }
  
  function getURLs($struct) {
    $r =array();
    if (is_array($struct)) {
      foreach ($struct as $k => $v) {
        if (preg_match('/^http:\/\//', $k) && !in_array($k, $r)) $r[] = $k;
        $sub_r = $this->getURLs($v);
        foreach ($sub_r as $sub_v) {
          if (!in_array($sub_v, $r)) $r[] = $sub_v;
        }
      }
    }
    elseif (preg_match('/^http:\/\//', $struct) && !in_array($struct, $r)) {
      $r[] = $struct;
    }
    return $r;
  }
  
  /*  */

}
