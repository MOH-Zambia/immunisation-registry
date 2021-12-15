<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCertificateAPIRequest;
use App\Http\Requests\API\UpdateCertificateAPIRequest;
use App\Models\Certificate;
use App\Repositories\CertificateRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class CertificateController
 * @package App\Http\Controllers\API
 */

class CertificateAPIController extends AppBaseController
{
    /** @var  CertificateRepository */
    private $certificateRepository;

    public function __construct(CertificateRepository $certificateRepo)
    {
        $this->certificateRepository = $certificateRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/certificates",
     *      summary="Get a listing of the Certificates.",
     *      tags={"Certificate"},
     *      description="Get all Certificates",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Certificate")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $certificates = $this->certificateRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($certificates->toArray(), 'Certificates retrieved successfully');
    }

    /**
     * @param CreateCertificateAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/certificates",
     *      summary="Store a newly created Certificate in storage",
     *      tags={"Certificate"},
     *      description="Store Certificate",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Certificate that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Certificate")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Certificate"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateCertificateAPIRequest $request)
    {
        $input = $request->all();

        $certificate = $this->certificateRepository->create($input);

        return $this->sendResponse($certificate->toArray(), 'Certificate saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/certificates/{id}",
     *      summary="Display the specified Certificate",
     *      tags={"Certificate"},
     *      description="Get Certificate",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Certificate",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Certificate"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Certificate $certificate */
        $certificate = $this->certificateRepository->find($id);

        if (empty($certificate)) {
            return $this->sendError('Certificate not found');
        }

        return $this->sendResponse($certificate->toArray(), 'Certificate retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateCertificateAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/certificates/{id}",
     *      summary="Update the specified Certificate in storage",
     *      tags={"Certificate"},
     *      description="Update Certificate",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Certificate",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Certificate that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Certificate")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Certificate"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateCertificateAPIRequest $request)
    {
        $input = $request->all();

        /** @var Certificate $certificate */
        $certificate = $this->certificateRepository->find($id);

        if (empty($certificate)) {
            return $this->sendError('Certificate not found');
        }

        $certificate = $this->certificateRepository->update($input, $id);

        return $this->sendResponse($certificate->toArray(), 'Certificate updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/certificates/{id}",
     *      summary="Remove the specified Certificate from storage",
     *      tags={"Certificate"},
     *      description="Delete Certificate",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Certificate",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Certificate $certificate */
        $certificate = $this->certificateRepository->find($id);

        if (empty($certificate)) {
            return $this->sendError('Certificate not found');
        }

        $certificate->delete();

        return $this->sendSuccess('Certificate deleted successfully');
    }
}
