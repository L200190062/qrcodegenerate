<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;


class CreateQrDocuments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'job_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'pdf_file' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'qr_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('job_id', 'bulk_qr_jobs', 'id', 'CASCADE', 'CASCADE', 'qr_documents_job_id_foreign');
        $this->forge->createTable('qr_documents');
    }

    public function down()
    {
        $this->forge->dropTable('qr_documents');
    }
}
