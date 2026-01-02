<?php

/**
 * Configuración de países y tipos de identificación fiscal.
 * 
 * Fácil de modificar: solo agrega nuevos países o tipos de ID fiscal.
 * 
 * Estructura:
 * - Código ISO del país (2 letras) => [
 *     'name' => Nombre del país,
 *     'tax_ids' => [
 *         'CODIGO' => 'Nombre descriptivo',
 *     ]
 * ]
 */

return [
    'ES' => [
        'name' => 'España',
        'tax_ids' => [
            'NIF' => 'NIF (Número de Identificación Fiscal)',
            'CIF' => 'CIF (Código de Identificación Fiscal)',
            'NIE' => 'NIE (Número Identidad Extranjero)',
        ],
    ],
    
    'CO' => [
        'name' => 'Colombia',
        'tax_ids' => [
            'NIT' => 'NIT (Número de Identificación Tributaria)',
            'RUT' => 'RUT (Registro Único Tributario)',
            'CC' => 'CC (Cédula de Ciudadanía)',
            'CE' => 'CE (Cédula de Extranjería)',
        ],
    ],
    
    'US' => [
        'name' => 'Estados Unidos',
        'tax_ids' => [
            'EIN' => 'EIN (Employer Identification Number)',
            'SSN' => 'SSN (Social Security Number)',
            'ITIN' => 'ITIN (Individual Taxpayer ID)',
        ],
    ],
    
    'CR' => [
        'name' => 'Costa Rica',
        'tax_ids' => [
            'CJ' => 'Cédula Jurídica',
            'CF' => 'Cédula Física',
            'DIMEX' => 'DIMEX (Documento de Identidad Migratorio)',
        ],
    ],
    
    'MX' => [
        'name' => 'México',
        'tax_ids' => [
            'RFC' => 'RFC (Registro Federal de Contribuyentes)',
            'CURP' => 'CURP (Clave Única de Registro de Población)',
        ],
    ],
    
    'AR' => [
        'name' => 'Argentina',
        'tax_ids' => [
            'CUIT' => 'CUIT (Clave Única de Identificación Tributaria)',
            'CUIL' => 'CUIL (Código Único de Identificación Laboral)',
            'DNI' => 'DNI (Documento Nacional de Identidad)',
        ],
    ],
];
