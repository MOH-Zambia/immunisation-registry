<?php namespace Tests\Repositories;

use App\Models\Certificate;
use App\Repositories\CertificateRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CertificateRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CertificateRepository
     */
    protected $certificateRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->certificateRepo = \App::make(CertificateRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_certificate()
    {
        $certificate = Certificate::factory()->make()->toArray();

        $createdCertificate = $this->certificateRepo->create($certificate);

        $createdCertificate = $createdCertificate->toArray();
        $this->assertArrayHasKey('id', $createdCertificate);
        $this->assertNotNull($createdCertificate['id'], 'Created Certificate must have id specified');
        $this->assertNotNull(Certificate::find($createdCertificate['id']), 'Certificate with given id must be in DB');
        $this->assertModelData($certificate, $createdCertificate);
    }

    /**
     * @test read
     */
    public function test_read_certificate()
    {
        $certificate = Certificate::factory()->create();

        $dbCertificate = $this->certificateRepo->find($certificate->id);

        $dbCertificate = $dbCertificate->toArray();
        $this->assertModelData($certificate->toArray(), $dbCertificate);
    }

    /**
     * @test update
     */
    public function test_update_certificate()
    {
        $certificate = Certificate::factory()->create();
        $fakeCertificate = Certificate::factory()->make()->toArray();

        $updatedCertificate = $this->certificateRepo->update($fakeCertificate, $certificate->id);

        $this->assertModelData($fakeCertificate, $updatedCertificate->toArray());
        $dbCertificate = $this->certificateRepo->find($certificate->id);
        $this->assertModelData($fakeCertificate, $dbCertificate->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_certificate()
    {
        $certificate = Certificate::factory()->create();

        $resp = $this->certificateRepo->delete($certificate->id);

        $this->assertTrue($resp);
        $this->assertNull(Certificate::find($certificate->id), 'Certificate should not exist in DB');
    }
}
