<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Certificate;

class CertificateApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_certificate()
    {
        $certificate = Certificate::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/certificates', $certificate
        );

        $this->assertApiResponse($certificate);
    }

    /**
     * @test
     */
    public function test_read_certificate()
    {
        $certificate = Certificate::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/certificates/'.$certificate->id
        );

        $this->assertApiResponse($certificate->toArray());
    }

    /**
     * @test
     */
    public function test_update_certificate()
    {
        $certificate = Certificate::factory()->create();
        $editedCertificate = Certificate::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/certificates/'.$certificate->id,
            $editedCertificate
        );

        $this->assertApiResponse($editedCertificate);
    }

    /**
     * @test
     */
    public function test_delete_certificate()
    {
        $certificate = Certificate::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/certificates/'.$certificate->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/certificates/'.$certificate->id
        );

        $this->response->assertStatus(404);
    }
}
