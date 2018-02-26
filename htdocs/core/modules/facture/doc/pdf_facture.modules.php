<?php
/**
 * Copyright (C) 2018 GROUPE SITEL <groupesitel@groupesitel>
 *
 * \file htdocs/core/modules/propale/doc/pdf_azur.modules.php
 * \ingroup propale
 * \brief Fichier de la classe permettant de generer les propales au modele Azur
 */
require_once DOL_DOCUMENT_ROOT . '/core/modules/propale/modules_propale.php';
require_once DOL_DOCUMENT_ROOT . '/product/class/product.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/company.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/pdf.lib.php';

require_once DOL_DOCUMENT_ROOT . '/includes/tecnickcom/tcpdf/tcpdf.php';

require_once(DOL_DOCUMENT_ROOT . '/custom/lib/Numbers/Words.php');

/**
 * Classe personnalisée pour la generation de PDF.
 */
class PDF extends TCPDF
{
    public function Footer()
    {
        $style = array('width' => 0.25, 'cap' => '', 'join' => '', 'dash' => 0, 'phase' => 0, 'color' => array(33, 33, 33));
        $margins = $this->getMargins();
        $dim = $this->getPageDimensions($this->PageNo());
        $w = $dim['wk'];
        $h = $dim['hk'];
        //var_dump($margins);
        $this->Line($margins['left'], $h - $margins['top'], $w - $margins['right'], $h - $margins['top'], $style);
        $html = '<p style="text-align:right;font-size:9pt;">Page ' . $this->PageNo() . ' / ' . $this->getAliasNbPages() . '</p>';
        $this->SetXY($margins['left'], $h - $margins['top']);
        $this->SetTextColor(0, 0, 0);
        $this->writeHTML($html, true, 0, true, true);
    }

    public function Header()
    {
        // Laisser le vide vu que l'entete se trouve deja dans la premiere page.
    }
}

/**
 * Class to generate PDF proposal Azur
 */
class pdf_facture extends ModelePDFFactures
{

    var $db;
    var $name;
    var $description;
    var $type;
    var $phpmin = array(
        4,
        3,
        0
    );
    // Minimum version of PHP required by module
    var $version = 'groupesitel';
    var $page_largeur;
    var $page_hauteur;
    var $format;

    var $marge_gauche;

    var $marge_droite;

    var $marge_haute;

    var $marge_basse;

    var $emetteur;
    // Objet societe qui emet

    /**
     * Constructor
     *
     * @param DoliDB $db Database handler
     */
    function __construct($db)
    {
        global $conf, $langs, $mysoc;

        $langs->load("main");
        $langs->load("bills");

        $this->db = $db;
        $this->name = "Facture";
        $this->description = 'Modèle de pdf facture';

        // Dimension page pour format A4
        $this->type = 'pdf';
        $formatarray = pdf_getFormat();
        $this->page_largeur = $formatarray['width'];
        $this->page_hauteur = $formatarray['height'];
        $this->format = array(
            $this->page_largeur,
            $this->page_hauteur
        );
        $this->marge_gauche = isset($conf->global->MAIN_PDF_MARGIN_LEFT) ? $conf->global->MAIN_PDF_MARGIN_LEFT : 10;
        $this->marge_droite = isset($conf->global->MAIN_PDF_MARGIN_RIGHT) ? $conf->global->MAIN_PDF_MARGIN_RIGHT : 10;
        $this->marge_haute = isset($conf->global->MAIN_PDF_MARGIN_TOP) ? $conf->global->MAIN_PDF_MARGIN_TOP : 10;
        $this->marge_basse = isset($conf->global->MAIN_PDF_MARGIN_BOTTOM) ? $conf->global->MAIN_PDF_MARGIN_BOTTOM : 10;

        $this->option_logo = 1; // Affiche logo
        $this->option_tva = 1; // Gere option tva FACTURE_TVAOPTION
        $this->option_modereg = 1; // Affiche mode reglement
        $this->option_condreg = 1; // Affiche conditions reglement
        $this->option_codeproduitservice = 1; // Affiche code produit-service
        $this->option_multilang = 1; // Dispo en plusieurs langues
        $this->option_escompte = 0; // Affiche si il y a eu escompte
        $this->option_credit_note = 0; // Support credit notes
        $this->option_freetext = 1; // Support add of a personalised text
        $this->option_draft_watermark = 1; // Support add of a watermark on drafts

        $this->franchise = !$mysoc->tva_assuj;

        // Get source company
        $this->emetteur = $mysoc;
        if (empty($this->emetteur->country_code)) {
            // By default, if was not defined
            $this->emetteur->country_code = substr($langs->defaultlang, -2);
        }
    }

    /**
     * Function to build pdf onto disk
     *
     * @param Object $object Object to generate
     * @param Translate $outputlangs Lang output object
     * @param string $srctemplatepath Full path of source filename for generator using a template file
     * @param int $hidedetails Do not show line details
     * @param int $hidedesc Do not show desc
     * @param int $hideref Do not show ref
     *
     * @return int 1=OK, 0=KO
     */
    function write_file($object, $outputlangs, $srctemplatepath = '', $hidedetails = 0, $hidedesc = 0, $hideref = 0)
    {
        global $user, $langs, $conf, $mysoc, $db, $hookmanager;

        if (!is_object($outputlangs)) {
            $outputlangs = $langs;
        }

        $outputlangs->load("main");
        $outputlangs->load("dict");
        $outputlangs->load("companies");
        $outputlangs->load("bills");
        $outputlangs->load("products");
        $outputlangs->load("orders");
        $outputlangs->load("deliveries");

        $nblignes = count($object->lines);

        if ($conf->facture->dir_output) {
            $object->fetch_thirdparty();
            $deja_regle = 0;
            // Definition of $dir and $file
            if ($object->specimen) {
                $dir = $conf->facture->dir_output;
                $file = $dir . "/SPECIMEN.pdf";
            } else {
                $objectref = dol_sanitizeFileName($object->ref);
                $dir = $conf->facture->dir_output . "/" . $objectref;
                $file = $dir . "/" . $objectref . ".pdf";
            }
            if (!file_exists($dir)) {
                if (dol_mkdir($dir) < 0) {
                    $this->error = $langs->transnoentities("ErrorCanNotCreateDir", $dir);
                    return 0;
                }
            }
            if (file_exists($dir)) {
                // Add pdfgeneration hook
                if (!is_object($hookmanager)) {
                    include_once DOL_DOCUMENT_ROOT . '/core/class/hookmanager.class.php';
                    $hookmanager = new HookManager($this->db);
                }
                $hookmanager->initHooks(
                    array(
                        'pdfgeneration'
                    )
                );
                $parameters = array(
                    'file' => $file,
                    'object' => $object,
                    'outputlangs' => $outputlangs
                );
                global $action;
                $reshook = $hookmanager->executeHooks('beforePDFCreation', $parameters, $object, $action); // Note that $action and $object may have been modified by some hooks
                // Create pdf instance
                // $pdf=pdf_getInstance($this->format);
                $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                $default_font_size = pdf_getPDFFontSize($outputlangs); // Must be after pdf_getInstance
                $pdf->SetAutoPageBreak(1, 0);

                // if (class_exists('TCPDF')) {
                //     $pdf->setPrintHeader(false);
                //     $pdf->setPrintFooter(false);
                // }
                $pdf->SetFont(pdf_getPDFFont($outputlangs));
                // Set path to the background PDF File
                if (empty($conf->global->MAIN_DISABLE_FPDI) && !empty($conf->global->MAIN_ADD_PDF_BACKGROUND)) {
                    $pagecount = $pdf->setSourceFile($conf->mycompany->dir_output . '/' . $conf->global->MAIN_ADD_PDF_BACKGROUND);
                    $tplidx = $pdf->importPage(1);
                }

                $pdf->Open();
                $pagenb = 0;
                $pdf->SetDrawColor(128, 128, 128);

                $pdf->SetTitle($outputlangs->convToOutputCharset($object->ref));
                $pdf->SetSubject($outputlangs->transnoentities("Invoice"));
                $pdf->SetCreator("GSitel " . DOL_VERSION);
                $pdf->SetAuthor($outputlangs->convToOutputCharset($user->getFullName($outputlangs)));
                $pdf->SetKeyWords($outputlangs->convToOutputCharset($object->ref) . " " . $outputlangs->transnoentities("CommercialProposal") . " " . $outputlangs->convToOutputCharset($object->thirdparty->name));
                if (!empty($conf->global->MAIN_DISABLE_PDF_COMPRESSION)) {
                    $pdf->SetCompression(false);
                }
                $pdf->SetMargins($this->marge_gauche, $this->marge_haute, $this->marge_droite); // Left, Top, Right

                // New page
                $pdf->AddPage();
                if (!empty($tplidx)) {
                    $pdf->useTemplate($tplidx);
                }

                $this->_pagehead($pdf, $object, 1, $outputlangs);

                $pdf->SetFont('', '', $default_font_size);

                $table = '<p></p><p></p><p></p><p></p><table width="100%" cellpadding="5" border="1">';
                $table .= '<tr style="background-color:#090407;color: white">';
                $table .= '<td width="60%">Désignation</td>';
                $table .= '<td width="15%" align="right">Prix unitaire</td>';
                $table .= '<td width="10%" align="right">Quantité</td>';
                $table .= '<td width="15%" align="right">Total HT</td></tr>';
                // Loop on each lines
                for ($i = 0; $i < $nblignes; $i++) {
                    // Ligne du produit
                    $table .= '<tr>';
                    $desc = pdf_getlinedesc($object, $i, $outputlangs, $hideref, $hidedesc);
                    $table .= '<td>' . $desc . '</td>';
                    $unit_price = pdf_getlineunit($object, $i, $outputlangs, $hidedetails);
                    $table .= '<td align="right">' . $unit_price . '</td>';
                    $qty = pdf_getlineqty($object, $i, $outputlangs, $hidedetails);
                    $table .= '<td align="right">' . $qty . '</td>';
                    $totalht = pdf_getlinetotalexcltax($object, $i, $outputlangs, $hidedetails);
                    $table .= '<td align="right">' . $totalht . '</td>';
                    $table .= '</tr>';
                }
                $table .= '</table>';
                $pdf->writeHTML($table, true, 0, true, true);

                // Total HT
                $table = '<p></p><table width="100%" border="0" cellpadding="5">';
                $table .= '<tr><td width="60%"></td>';
                $table .= '<td width="25%" align="right" style="border-bottom:1px solid #000;">' . $outputlangs->transnoentities("TotalHT") . '</td>';
                $total_ht = ($conf->multicurrency->enabled && $object->mylticurrency_tx != 1 ? $object->multicurrency_total_ht : $object->total_ht);
                $table .= '<td width="15%" align="right" style="border-bottom:1px solid #000;">' . price($total_ht + (!empty($object->remise) ? $object->remise : 0), 0, $outputlangs) . '</td>';
                $table .= '</tr>';
                if ($object->total_tva > 0) {
                    $table .= '<tr><td></td>';
                    $table .= '<td align="right" style="border-bottom:1px solid #000;">' . $outputlangs->transnoentities("TotalVAT") . '</td>';
                    $table .= '<td align="right" style="border-bottom:1px solid #000;">' . price($object->total_tva, 0, $outputlangs) . '</td>';
                    $table .= '</tr>';
                }
                // Total TTC
                $ttc_text = $outputlangs->transnoentities("TotalTTC");
                $total_ttc = ($conf->multicurrency->enabled && $object->multiccurency_tx != 1) ? $object->multicurrency_total_ttc : $object->total_ttc;
                $total_ttc_lettre = Numbers_Words::toWords($total_ttc, 'fr');
                $table .= '<tr><td></td>';
                $table .= '<td align="right" style="border-bottom:1px solid #000;">' . $ttc_text . '</td>';
                $table .= '<td align="right" style="border-bottom:1px solid #000;">' . price($total_ttc, 0, $outputlangs) . '</td>';
                $table .= '</tr>';

                $table .= '</table>';
                $table .= '<p><span style="font-weight: normal">Arreté la presente facture à la sommme de : </span><span style="font-weight: bold">' . $total_ttc_lettre . ' Francs CFA</span></p>';
                $pdf->writeHTML($table, true, 0, true, true);
                // Affiche zone infos
                $table = '<p></p><table width="100%" cellpadding="5">';
                // Show shipping date
                if (!empty($object->date_livraison)) {
                    $outputlangs->load("sendings");
                    $table .= '<tr><td width="60%"></td>';
                    $table .= '<td width="25%">' . $outputlangs->transnoentities("DateDeliveryPlanned") . ':</td>';
                    $table .= '<td width="15%">' . dol_print_date($object->date_livraison, "daytext", false, $outputlangs, true) . '</td>';
                    $table .= '</tr>';
                } elseif ($object->availability_code || $object->availability) {
                    // Show availability conditions
                    $lib_availability = $outputlangs->transnoentities("AvailabilityType" . $object->availability_code) != ('AvailabilityType' . $object->availability_code) ? $outputlangs->transnoentities("AvailabilityType" . $object->availability_code) : $outputlangs->convToOutputCharset($object->availability);
                    $lib_availability = str_replace('\n', "\n", $lib_availability);
                    $table .= '<tr><td></td>';
                    $table .= '<td>' . $outputlangs->transnoentities("AvailabilityPeriod") . ':</td>';
                    $table .= '<td>' . $lib_availability . '</td>';
                    $table .= '</tr>';
                }
                if ($object->type != 2) {
                    // Show payments conditions
                    if ($object->type != 2 && ($object->cond_reglement_code || $object->cond_reglement)) {
                        $lib_condition_paiement = $outputlangs->transnoentities("PaymentCondition" . $object->cond_reglement_code) != ('PaymentCondition' . $object->cond_reglement_code) ? $outputlangs->transnoentities("PaymentCondition" . $object->cond_reglement_code) : $outputlangs->convToOutputCharset($object->cond_reglement_doc);
                        $lib_condition_paiement = str_replace('\n', "\n", $lib_condition_paiement);
                        $table .= '<tr><td></td>';
                        $table .= '<td>' . $outputlangs->transnoentities("PaymentConditions") . ':</td>';
                        $table .= '<td>' . $lib_availability . '</td>';
                        $table .= '</tr>';
                    }

                    // Show payment mode
                    if ($object->mode_reglement_code && $object->mode_reglement_code != 'CHQ' && $object->mode_reglement_code != 'VIR') {
                        $lib_mode_reg = $outputlangs->transnoentities("PaymentType" . $object->mode_reglement_code) != ('PaymentType' . $object->mode_reglement_code) ? $outputlangs->transnoentities("PaymentType" . $object->mode_reglement_code) : $outputlangs->convToOutputCharset($object->mode_reglement);
                        $table .= '<tr><td></td>';
                        $table .= '<td>' . $outputlangs->transnoentities("PaymentMode") . ':</td>';
                        $table .= '<td>' . $lib_mode_reg . '</td>';
                        $table .= '</tr>';
                    }
                    // Show payment mode CHQ
                    if (empty($object->mode_reglement_code) || $object->mode_reglement_code == 'CHQ') {
                        // Si mode reglement non force ou si force a CHQ
                        if (!empty($conf->global->FACTURE_CHQ_NUMBER)) {
                            $diffsizetitle = (empty($conf->global->PDF_DIFFSIZE_TITLE) ? 3 : $conf->global->PDF_DIFFSIZE_TITLE);
                            if ($conf->global->FACTURE_CHQ_NUMBER > 0) {
                                $account = new Account($this->db);
                                $account->fetch($conf->global->FACTURE_CHQ_NUMBER);
                                $table .= '<tr><td></td>';
                                $table .= '<td colspan="2">' . $outputlangs->transnoentities('PaymentByChequeOrderedTo', $account->proprio) . ':</td>';
                                $table .= '</tr>';
                                if (empty($conf->global->MAIN_PDF_HIDE_CHQ_ADDRESS)) {
                                    $table .= '<tr><td></td>';
                                    $table .= '<td colspan="2">' . $outputlangs->convToOutputCharset($account->owner_address) . ':</td>';
                                    $table .= '</tr>';
                                }
                            }
                            if ($conf->global->FACTURE_CHQ_NUMBER == -1) {
                                $table .= '<tr><td></td>';
                                $table .= '<td colspan="2">' . $outputlangs->transnoentities('PaymentByChequeOrderedTo', $this->emetteur->name) . ':</td>';
                                $table .= '</tr>';
                                if (empty($conf->global->MAIN_PDF_HIDE_CHQ_ADDRESS)) {
                                    $table .= '<tr><td></td>';
                                    $table .= '<td colspan="2">' . $outputlangs->convToOutputCharset($this->emetteur->getFullAddress()) . ':</td>';
                                    $table .= '</tr>';
                                }
                            }
                        }
                    }
                }

                $table .= '</table>';
                $pdf->writeHTML($table, true, 0, true, true);

                // Pied de page
                // $this->_pagefoot($pdf, $object, $outputlangs);
                // if (method_exists($pdf, 'AliasNbPages')) {
                //     $pdf->AliasNbPages();
                // }


                $pdf->Close();
                $pdf->Output($file, 'F');

                // Add pdfgeneration hook
                $hookmanager->initHooks(
                    array(
                        'pdfgeneration'
                    )
                );
                $parameters = array(
                    'file' => $file,
                    'object' => $object,
                    'outputlangs' => $outputlangs
                );
                global $action;
                $reshook = $hookmanager->executeHooks('afterPDFCreation', $parameters, $this, $action); // Note that $action and $object may have been modified by some hooks

                if (!empty($conf->global->MAIN_UMASK)) {
                    @chmod($file, octdec($conf->global->MAIN_UMASK));
                }
                return 1; // Pas d'erreur
            } else {
                $this->error = $langs->trans("ErrorCanNotCreateDir", $dir);
                return 0;
            }
        } else {
            $this->error = $langs->trans("ErrorConstantNotDefined", "FAC_OUTPUTDIR");
            return 0;
        }
        $this->error = $langs->trans("ErrorUnknown");
        return 0; // Erreur par defaut
    }

    /**
     * Show top header of page.
     *
     * @param PDF $pdf Object PDF
     * @param Object $object Object to show
     * @param int $showaddress 0=no, 1=yes
     * @param Translate $outputlangs Object lang for output
     *
     * @return void
     */
    function _pagehead(&$pdf, $object, $showaddress, $outputlangs)
    {
        global $conf, $langs;

        $outputlangs->load("main");
        $outputlangs->load("dict");
        $outputlangs->load("companies");
        $outputlangs->load("bills");
        $outputlangs->load("products");

        $default_font_size = pdf_getPDFFontSize($outputlangs);

        pdf_pagehead($pdf, $outputlangs, $this->page_hauteur);

        $pdf->SetTextColor(0, 0, 60);
        //$pdf->SetFont('', 'B', $default_font_size + 3);

        $table = '<table border="0" width="100%"><tr>';

        $posy = $this->marge_haute;
        $posx = $this->page_largeur - $this->marge_droite - 100;
        $pdf->SetXY($this->marge_gauche, $posy);
        // Logo
        $logo = $conf->mycompany->dir_output . '/logos/' . $this->emetteur->logo;
        if ($this->emetteur->logo) {
            if (is_readable($logo)) {
                $table .= '<td width="50%"></td>';
                $height = pdf_getHeightForLogo($logo);
                $pdf->Image($logo, $this->marge_gauche, $posy, 0, $height); // width=0 (auto)
            } else {
                $table .= '<td width="50%" style="color:#660000;">';
                $table .= '' . $outputlangs->transnoentities("ErrorLogoFileNotFound", $logo);
                $table .= ' ' . $outputlangs->transnoentities("ErrorGoToGlobalSetup");
                $table .= '</td>';
            }
        } else {
            $text = $this->emetteur->name;
            $table .= '<td width="50%">';
            $table .= '' . $outputlangs->convToOutputCharset($text);
            $table .= '</td>';
        }

        $table .= '<td width="50%" align="right">';
        // Titre
        $title = $outputlangs->transnoentities("Invoice");
        $table .= '<span style="font-size:18pt;font-weight:bold">' . $title . '</span><br/>';
        // Ref
        $table .= '<span style="font-size:10pt">' . $outputlangs->convToOutputCharset($object->ref) . '</span><br/>';
        if ($object->ref_client) {
            // Customer ref
            $table .= '<span style="font-size:10pt">' . $outputlangs->transnoentities("RefCustomer") . " : " . $outputlangs->convToOutputCharset($object->ref_client) . '</span><br/>';
        }
        //Date
        $table .= '<span style="font-size:10pt">' . $outputlangs->transnoentities("DateInvoice") . " : " . dol_print_date($object->date, "day", false, $outputlangs, true) . '</span><br/>';
        if ($object->type != 2) {
            $table .= '<span style="font-size:10pt">' . $outputlangs->transnoentities("DateDue") . " : " . dol_print_date($object->date, "day", false, $outputlangs, true) . '</span><br/>';
        }
        if ($object->thirdparty->code_client) {
            // Customer code
            $table .= '<span style="font-size:10pt">' . $outputlangs->transnoentities("CustomerCode") . " : " . $outputlangs->transnoentities($object->thirdparty->reference_client) . '</span><br/>';
        }
        if ($this->pdf_getLinkedObjects($object, $outputlangs)) {
            $table .= $this->pdf_getLinkedObjects($object, $outputlangs);
        }
        $table .= '</td></tr></table>';
        $pdf->writeHTML($table, true, 0, true, true);

        $posy = $pdf->GetY() + 10;
        // Show list of linked objects
        //$posy = pdf_writeLinkedObjects($pdf, $object, $outputlangs, $posx, $posy, 100, 3, 'R', $default_font_size);

        $posx = $this->marge_gauche;
        $hauteur_cadre = 30;
        $style = array(
            'width' => 0.25,
            'cap' => 'butt',
            'join' => 'miter',
            'dash' => '0',
            'color' => array(
                145,
                62,
                152
            )
        );
        //$pdf->SetAlpha(0.25);
        // Contour emetteur
        $pdf->RoundedRect(
            $posx, $posy, 90, $hauteur_cadre, 8.0, '0101', 'DF', $style, array(
                255,
                255,
                255
            )
        );

        $posx = $this->page_largeur - $this->marge_droite - 90;
        $style = array(
            'width' => 0.25,
            'cap' => 'butt',
            'join' => 'miter',
            'dash' => '0',
            'color' => array(
                100,
                100,
                100
            )
        );
        // Contour recepteur
        $pdf->RoundedRect(
            $posx, $posy, 90, $hauteur_cadre, 8.0, '0101', 'DF', $style, array(
                255,
                255,
                255
            )
        );
        //$pdf->SetAlpha(1.0);

        if ($showaddress) {
            // Sender properties
            $carac_emetteur = '';
            // Add internal contact of proposal if defined
            $arrayidcontact = $object->getIdContact('internal', 'SALESREPFOLL');
            if (count($arrayidcontact) > 0) {
                $object->fetch_user($arrayidcontact[0]);
                $labelbeforecontactname = ($outputlangs->transnoentities("FromContactName") != 'FromContactName' ? $outputlangs->transnoentities("FromContactName") : $outputlangs->transnoentities("Name"));
                $carac_emetteur .= ($carac_emetteur ? "\n" : '') . $labelbeforecontactname . " " . $outputlangs->convToOutputCharset($object->user->getFullName($outputlangs)) . "\n";
            }
            $carac_emetteur .= pdf_build_address($outputlangs, $this->emetteur, $object->thirdparty);


            $table = '';

            $table .= '<table border="0" width="100%" cellpadding="10"><tr>';
            $table .= '<td width="50%">';
            $table .= '<span style="font-size:11pt;font-weight:bold">' . $outputlangs->transnoentities("BillFrom") . ' :</span><br/><br/>';
            $table .= '<span style="font-size:10pt">' . $outputlangs->convToOutputCharset($this->emetteur->name) . '</span><br/>';
            $table .= '<span style="font-size:10pt">' . $carac_emetteur . '</span>';
            $table .= '</td>';
            // If CUSTOMER contact defined, we use it
            $usecontact = false;
            $arrayidcontact = $object->getIdContact('external', 'CUSTOMER');
            if (count($arrayidcontact) > 0) {
                $usecontact = true;
                $result = $object->fetch_contact($arrayidcontact[0]);
            }

            // Recipient name
            // On peut utiliser le nom de la societe du contact
            if ($usecontact && !empty($conf->global->MAIN_USE_COMPANY_NAME_OF_CONTACT)) {
                $thirdparty = $object->contact;
            } else {
                $thirdparty = $object->thirdparty;
            }
            $carac_client_name = pdfBuildThirdpartyName($thirdparty, $outputlangs);
            $carac_client = pdf_build_address($outputlangs, $this->emetteur, $object->thirdparty, ($usecontact ? $object->contact : ''), $usecontact, 'target', $object);
            // Show recipient
            $table .= '<td width="50%">';
            $table .= '<span style="font-size:11pt;font-weight:bold">&nbsp;&nbsp;&nbsp;&nbsp;' . $outputlangs->transnoentities("BillTo") . ' :</span><br/><br/>';
            // Show recipient name
            $table .= '<span style="font-size:10pt">&nbsp;&nbsp;&nbsp;&nbsp;' . $carac_client_name . '</span><br/>';
            // Show recipient information
            $table .= '<span style="font-size:10pt">&nbsp;&nbsp;&nbsp;&nbsp;' . $carac_client . '</span>';
            $table .= '</td>';

            $table .= '</tr></table>';
            $pdf->writeHTML($table, true, 0, true, true);
        }

    }

    /**
     *    Return linked objects to use for document generation.
     *  Warning: To save space, this function returns only one link per link type (all links are concated on same record string). This function is used by pdf_writeLinkedObjects
     *
     * @param    object $object Object
     * @param    Translate $outputlangs Object lang for output
     * @return    array                       Linked objects
     */
    function pdf_getLinkedObjects($object, $outputlangs)
    {
        global $hookmanager;

        $linkedobjects = array();

        $object->fetchObjectLinked();

        foreach ($object->linkedObjects as $objecttype => $objects) {
            if ($objecttype == 'propal') {
                $outputlangs->load('propal');

                foreach ($objects as $elementobject) {
                    $linkedobjects[$objecttype]['ref_title'] = $outputlangs->transnoentities("RefProposal");
                    $linkedobjects[$objecttype]['ref_value'] = $outputlangs->transnoentities($elementobject->ref);
                    $linkedobjects[$objecttype]['date_title'] = $outputlangs->transnoentities("DatePropal");
                    $linkedobjects[$objecttype]['date_value'] = dol_print_date($elementobject->date, 'day', '', $outputlangs);
                }
            } else if ($objecttype == 'commande') {
                $outputlangs->load('orders');
                foreach ($objects as $elementobject) {
                    $linkedobjects[$objecttype]['ref_title'] = $outputlangs->transnoentities("RefOrder");
                    $linkedobjects[$objecttype]['ref_value'] = $outputlangs->transnoentities($elementobject->ref) . ($elementobject->ref_client ? ' (' . $elementobject->ref_client . ')' : '') . ($elementobject->ref_supplier ? ' (' . $elementobject->ref_supplier . ')' : '');
                    $linkedobjects[$objecttype]['date_title'] = $outputlangs->transnoentities("OrderDate");
                    $linkedobjects[$objecttype]['date_value'] = dol_print_date($elementobject->date, 'day', '', $outputlangs);
                }
            } else if ($objecttype == 'contrat') {
                $outputlangs->load('contracts');
                foreach ($objects as $elementobject) {
                    $linkedobjects[$objecttype]['ref_title'] = $outputlangs->transnoentities("RefContract");
                    $linkedobjects[$objecttype]['ref_value'] = $outputlangs->transnoentities($elementobject->ref);
                    $linkedobjects[$objecttype]['date_title'] = $outputlangs->transnoentities("DateContract");
                    $linkedobjects[$objecttype]['date_value'] = dol_print_date($elementobject->date_contrat, 'day', '', $outputlangs);
                }
            }
        }

        // For add external linked objects
        if (is_object($hookmanager)) {
            $parameters = array('linkedobjects' => $linkedobjects, 'outputlangs' => $outputlangs);
            $action = '';
            $hookmanager->executeHooks('pdf_getLinkedObjects', $parameters, $object, $action);    // Note that $action and $object may have been modified by some hooks
            if (!empty($hookmanager->resArray)) $linkedobjects = $hookmanager->resArray;
        }

        /**
         * Traitement de l'affichage pour avoir l'information sur une seul ligne
         *
         */
        $objectlies = '';
        if (!empty($linkedobjects)) {
            foreach ($linkedobjects as $linkedobject) {
                $reftoshow = $linkedobject["ref_title"] . ' : ' . $linkedobject["ref_value"];
                if (!empty($linkedobject["date_value"])) {
                    $reftoshow .= ' / ' . $linkedobject["date_value"];
                }

                $objectlies .= '<span span style="font-size:10pt">' . $reftoshow . '</span><br/>';
            }
        }

        return $objectlies;
    }
}
        
        