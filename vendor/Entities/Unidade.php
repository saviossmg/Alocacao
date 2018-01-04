<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Unidade
 *
 * @ORM\Table(name="unidade", indexes={@ORM\Index(name="fk_unidade_vwServidores1_idx", columns={"DiretorGeral"}), @ORM\Index(name="fk_unidade_vwServidores2_idx", columns={"Administrador"})})
 * @ORM\Entity
 */
class Unidade
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
     * @ORM\Column(name="Nome", type="string", length=50, nullable=false)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="Endereco", type="string", length=100, nullable=false)
     */
    private $endereco;

    /**
     * @var integer
     *
     * @ORM\Column(name="Cep", type="integer", nullable=false)
     */
    private $cep;

    /**
     * @var float
     *
     * @ORM\Column(name="Latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="Longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Ativo", type="boolean", nullable=false)
     */
    private $ativo;

    /**
     * @var \Vwservidor
     *
     * @ORM\ManyToOne(targetEntity="Vwservidor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="DiretorGeral", referencedColumnName="Id")
     * })
     */
    private $diretorgeral;

    /**
     * @var \Vwservidor
     *
     * @ORM\ManyToOne(targetEntity="Vwservidor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Administrador", referencedColumnName="Id")
     * })
     */
    private $administrador;


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
     * Set nome
     *
     * @param string $nome
     *
     * @return Unidade
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set endereco
     *
     * @param string $endereco
     *
     * @return Unidade
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;

        return $this;
    }

    /**
     * Get endereco
     *
     * @return string
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * Set cep
     *
     * @param integer $cep
     *
     * @return Unidade
     */
    public function setCep($cep)
    {
        $this->cep = $cep;

        return $this;
    }

    /**
     * Get cep
     *
     * @return integer
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Unidade
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Unidade
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set ativo
     *
     * @param boolean $ativo
     *
     * @return Unidade
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
     * Set diretorgeral
     *
     * @param \Vwservidor $diretorgeral
     *
     * @return Unidade
     */
    public function setDiretorgeral(\Vwservidor $diretorgeral = null)
    {
        $this->diretorgeral = $diretorgeral;

        return $this;
    }

    /**
     * Get diretorgeral
     *
     * @return \Vwservidor
     */
    public function getDiretorgeral()
    {
        return $this->diretorgeral;
    }

    /**
     * Set administrador
     *
     * @param \Vwservidor $administrador
     *
     * @return Unidade
     */
    public function setAdministrador(\Vwservidor $administrador = null)
    {
        $this->administrador = $administrador;

        return $this;
    }

    /**
     * Get administrador
     *
     * @return \Vwservidor
     */
    public function getAdministrador()
    {
        return $this->administrador;
    }
}

