<?php

namespace App\Helpers\Prestamos;

use App\Helpers\Text\Translate;
use \Illuminate\Http\Request;
use Exception;
use App\Helpers\Base\Status;
use App\Helpers\Base\Date;
use App\Helpers\Address\AddressInterface;
use App\Helpers\Base\Tools;
use App\Models\Financiamientos;
use App\Models\Intereses;
use App\Models\PlanCuotas;
use App\Models\Plazos;
use App\Models\Prestamos;
use App\Helpers\Account\AccountInterface;
use App\Models\DiasPagos;
use App\Models\Pagos;
use App\Models\PlanesPagos;

class PrestamosInterface
{
    /**
     * @var Translate
     */
    protected $translate;

    /**
     * @var Status
     */
    protected $status;

    /**
     * @var Date
     */
    protected $date;

    /**
     * @var AddressInterface
     */
    protected $addressInterface;

    /**
     * @var Tools
     */
    protected $tools;

    /**
     * @var AccountInterface
     */
    protected $accountInterface;

    public function __construct()
    {
        $this->translate = new Translate();
        $this->status = new Status();
        $this->date = new Date();
        $this->addressInterface = new AddressInterface();
        $this->tools = new Tools();
        $this->accountInterface = new AccountInterface();
    }

    /**
     * @return array
     */
    public function getAllPrestamos()
    {
        $data = [];
        $allPrestamos = Prestamos::all();

        foreach ($allPrestamos as $key => $prestamo) {
            $data[] = $this->getPrestamoArray($prestamo);
        }

        return $data;
    }

    /**
     * @param Prestamos $prestamo
     * @return array|null
     */
    public function getPrestamoArray($prestamo)
    {
        if (is_null($prestamo)) {
            return null;
        }

        return array(
            "id" => $prestamo->id,
            "monto_base" => $prestamo->monto_base,
            "monto_interes" => $prestamo->monto_interes,
            "monto_total" => $prestamo->monto_total,
            "monto_pago" => $prestamo->monto_pago,
            "mora" => $prestamo->mora,
            "fecha_prestamo" => $prestamo->fecha_prestamo,
            "fecha_finalizacion" => $prestamo->fecha_finalizacion,
            "estado" => $prestamo->getEstado->toArray(),
            "customer" => $this->accountInterface->getCustomerArray($prestamo->getCustomer),
            "plan_cuota" => $this->getPlanCuotaArray($prestamo->getPlanCuota),
            "plan_pago" => $this->getPlanPagoPrestamoArray($prestamo->getPlanesPagos),
            "historial_pago" => $this->getHistorialPago($prestamo->getHistorialPagos)
        );
    }

    /**
     * @param DiasPagos[] $historialPago
     * @return array|null
     */
    public function getHistorialPago($historialPago)
    {
        if (is_null($historialPago)) {
            return null;
        }

        $data = [];
        foreach ($historialPago as $key => $item) {
            $data[] = $this->getDiaPagoArray($item);
        }

        return $data;
    }

    /**
     * @param DiasPagos $diaPago
     * @return array|null
     */
    public function getDiaPagoArray($diaPago)
    {
        if (is_null($diaPago)) {
            return null;
        }

        return array(
            "pagado" => $diaPago->pagado,
            "mora" => $diaPago->mora,
            "pago" => $this->getPagoArray($diaPago->getPago),
            "cobrador" => $this->getCobradorArray($diaPago->getCobrador)
        );
    }

    /**
     * @param Pagos $cobrador
     * @return array|null
     */
    public function getCobradorArray($cobrador)
    {
        if (is_null($cobrador)) {
            return null;
        }

        return array(
            "id" => $cobrador->id,
            "name" => $cobrador->name,
            "email" => $cobrador->email,
            "telefono" => $cobrador->telefono,
            "token" => $cobrador->token,
            "status" => $cobrador->status,
            "partner" => $this->accountInterface->getAccountPartnerArray($cobrador->accountPartner)
        );
    }

    /**
     * @param PlanesPagos[] $planesPagos
     * @return array|null
     */
    public function getPlanPagoPrestamoArray($planesPagos)
    {
        if (is_null($planesPagos)) {
            return null;
        }

        $data = [];
        foreach ($planesPagos as $key => $item) {
            $data[] = $this->getPagoArray($item->getPago);
        }

        return $data;
    }

    /**
     * @param Pagos $pago
     * @return array|null
     */
    public function getPagoArray($pago)
    {
        if (is_null($pago)) {
            return null;
        }

        return array(
            "id" => $pago->id,
            "num_pago" => $pago->num_pago,
            "monto" => $pago->monto,
            "mora" => $pago->mora,
            "fecha_pago" => $pago->fecha_pago,
            "fecha_pagado" => $pago->fecha_pagado,
            "estado" => $pago->getEstado->toArray(),
        );
    }

    /**
     * @return array
     */
    public function getAllIntereses()
    {
        $allIntereses = Intereses::all();

        return $allIntereses->toArray();
    }

    /**
     * @return array
     */
    public function getAllPlazos()
    {
        $allPlazos = Plazos::all();

        return $allPlazos->toArray();
    }

    /**
     * @return array
     */
    public function getAllPlanesCuotas()
    {
        $data = [];
        $allPlanesCuotas = PlanCuotas::all();

        foreach ($allPlanesCuotas as $key => $planCuota) {
            $data[] = $this->getPlanCuotaArray($planCuota);
        }

        return $data;
    }

    /**
     * @param PlanCuotas $planCuotas
     * @return array|null
     */
    public function getPlanCuotaArray($planCuotas)
    {
        if (is_array($planCuotas)) {
            return null;
        }

        return array(
            "id" => $planCuotas->id,
            "monto_base" => $planCuotas->monto_base,
            "monto_interes" => $planCuotas->monto_interes,
            "monto_total" => $planCuotas->monto_total,
            "financiamiento" => $planCuotas->getFinanciamiento->toArray(),
            "interes" => $planCuotas->getInteres->toArray(),
            "plazos" => $planCuotas->getPlazo->toArray()
        );
    }

    /**
     * @return array
     */
    public function getAllFinanciamiento()
    {
        $data = [];
        $allFinanciamientos = Financiamientos::all();

        foreach ($allFinanciamientos as $key => $financiamiento) {
            $data[] = $this->getFinanciamientoArray($financiamiento);
        }

        return $data;
    }

    /**
     * @param Financiamientos $financiamiento
     * @return array|null
     */
    public function getFinanciamientoArray($financiamiento)
    {
        if (is_null($financiamiento)) {
            return null;
        }

        return array(
            "id" => $financiamiento->id,
            "name" => $financiamiento->name,
            "code" => $financiamiento->code,
            "value" => $financiamiento->value,
            "status" => $financiamiento->status
        );
    }
}
