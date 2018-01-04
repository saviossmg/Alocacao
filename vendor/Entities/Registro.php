<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Registro
 *
 * @ORM\Table(name="registro", indexes={@ORM\Index(name="fk_registros_registros_idx", columns={"IdPai"}), @ORM\Index(name="fk_registros_entidade1_idx", columns={"IdEntidade"})})
 * @ORM\Entity
 */
class Registro
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Descricao", type="string", length=50, nullable=false)
     */
    private $descricao;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Ativo", type="boolean", nullable=false)
     */
    private $ativo;

    /**
     * @var \Entidade
     *
     * @ORM\ManyToOne(targetEntity="Entidade")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdEntidade", referencedColumnName="Id")
     * })
     */
    private $identidade;

    /**
     * @var \Registro
     *
     * @ORM\ManyToOne(targetEntity="Registro")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdPai", referencedColumnName="Id")
     * })
     */
    private $idpai;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set descricao
     *
     * @param string $descricao
     *
     * @return Registro
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get descricao
     *
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set ativo
     *
     * @param boolean $ativo
     *
     * @return Registro
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * Get ativo
     *
     * @return boolean
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Set identidade
     *
     * @param \Entidade $identidade
     *
     * @return Registro
     */
    public function setIdentidade(\Entidade $identidade = null)
    {
        $this->identidade = $identidade;

        return $this;
    }

    /**
     * Get identidade
     *
     * @return \Entidade
     */
    public function getIdentidade()
    {
        return $this->identidade;
    }

    /**
     * Set idpai
     *
     * @param \Registro $idpai
     *
     * @return Registro
     */
    public function setIdpai(\Registro $idpai = null)
    {
        $this->idpai = $idpai;

        return $this;
    }

    /**
     * Get idpai
     *
     * @return \Registro
     */
    public function getIdpai()
    {
        return $this->idpai;
    }
}

