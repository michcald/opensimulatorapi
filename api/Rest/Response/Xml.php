<?php

class Rest_Response_Xml extends Rest_Response
{
    protected $contentType = 'application/xml';
    
    public function formatData($data)
    {
        $temp = array();
        
        foreach($data as $key => $value) {
            $temp[$key]['object'] = $value;
        }
        
        //return "<pre>".print_r($temp, true)."</pre>";
        
        $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><list></list>");
        $this->array_to_xml($temp, $xml);
        
        return $xml->asXML();
    }
    
    // function defination to convert array to xml
    private function array_to_xml($student_info, &$xml_student_info)
    {
        foreach($student_info as $key => $value)
        {
            if(is_array($value))
            {
                if(!is_numeric($key))
                {
                    $subnode = $xml_student_info->addChild("$key");
                    $this->array_to_xml($value, $subnode);
                }
                else
                {
                    $this->array_to_xml($value, $xml_student_info);
                }
            }
            else
            {
                $xml_student_info->addChild("$key","$value");
            }
        }
    }
}